<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Mencegah error Undefined type 'Log'

class DeviceMonitorController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Keamanan Lapis Pertama
        $apiKey = $request->header('X-API-KEY');
        if ($apiKey !== env('API_MONITOR_KEY', 'tgasecret2026')) {
            return response()->json(['error' => 'Akses Ditolak. API Key tidak valid.'], 401);
        }

        // 1.5 Validasi input wajib dari client
        $request->validate([
            'hostname' => 'required|string',
        ]);

        try {
            // 2. Proses File Laporan Baterai (Jika dilampirkan oleh Agent)
            $reportPath = null;
            if ($request->hasFile('battery_report')) {
                $file = $request->file('battery_report');

                // Format nama file: TGA-HOSTNAME-TIMESTAMP.html
                // preg_replace digunakan untuk membuang spasi/karakter aneh dari hostname agar aman di URL
                $cleanHostname = preg_replace('/[^A-Za-z0-9\-]/', '', $request->hostname);
                $fileName = 'TGA-' . $cleanHostname . '-' . time() . '.html';

                // Simpan ke storage/app/public/battery_reports
                $reportPath = $file->storeAs('battery_reports', $fileName, 'public');
            }

            // 3. Susun Data Dinamis untuk Database
            $updateData = [
                'serial_number' => $request->serial_number,
                'logged_in_user' => $request->logged_in_user,
                'os_version' => $request->os_version,
                'pc_model' => $request->pc_model, // Data Baru
                'cpu_name' => $request->cpu_name, // Data Baru
                'ip_address' => $request->ip_address,
                'mac_address' => $request->mac_address,
                'wifi_ssid' => $request->wifi_ssid, // Data Baru
                'free_disk_c_percent' => $request->free_disk_c_percent,
                'free_disk_d_percent' => $request->free_disk_d_percent,
                'total_ram_gb' => $request->total_ram_gb, // Data Baru
                'ram_usage_percent' => $request->ram_usage_percent,
                'cpu_usage_percent' => $request->cpu_usage_percent, // Data Baru
                'battery_health_percent' => $request->battery_health_percent,
                'last_boot_time' => $request->last_boot_time,
                'uptime_days' => $request->uptime_days, // Data Baru
                'last_sync' => now(),
            ];

            if ($reportPath !== null) {
                $updateData['battery_report_path'] = $reportPath;
            }

            // 4. Simpan atau Timpa Data Perangkat ke Tabel 'devices'
            $device = Device::updateOrCreate(
                ['hostname' => $request->hostname],
                $updateData
            );

            // 5. Logika Pembuatan Tiket Otomatis (Storage C: atau D: di bawah 10%)
            $criticalDisks = [];
            if ($request->free_disk_c_percent !== null && $request->free_disk_c_percent < 10) {
                $criticalDisks[] = 'C:';
            }
            if ($request->free_disk_d_percent !== null && $request->free_disk_d_percent < 5) {
                $criticalDisks[] = 'D:';
            }

            if (!empty($criticalDisks)) {
                $diskNames = implode(' dan ', $criticalDisks); // Hasil: "C:" atau "D:" atau "C: dan D:"

                // Cek apakah tiket peringatan yang sama masih open/pending
                $existingTicket = Ticket::where('title', 'like', "%Disk Space Kritis: {$device->hostname}%")
                    ->whereIn('status', ['open', 'in_progress', 'pending'])
                    ->first();

                if (!$existingTicket) {
                    $adminId = User::where('role', 'admin')->first()->id ?? 1;

                    Ticket::create([
                        'user_id' => $adminId, // Ambil dinamis dari database, fallback ke 1
                        'category_id' => 1,
                        'item_type' => 'Laptop / PC',
                        'title' => "Disk Space Kritis: {$device->hostname}",
                        'description' => "Peringatan Otomatis Sistem:\n\nPenyimpanan ($diskNames) pada perangkat {$device->hostname} (User: {$device->logged_in_user}) tersisa kurang dari 10%.\nKondisi ini dapat menyebabkan gagal sistem atau kehilangan pekerjaan agen. Segera jadwalkan maintenance untuk pembersihan data.",
                        'priority' => 'high',
                        'status' => 'open',
                        'location' => 'Monitoring System (Auto-Generated)',
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data perangkat berhasil disinkronisasi ke server.'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal Sinkronisasi Device: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan sistem saat memproses data.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
