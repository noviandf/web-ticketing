<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeviceController extends Controller
{
   public function index(Request $request)
{
    // Memulai query
    $query = Device::query();

    // Logika Pencarian Nama (Hostname)
    if ($request->has('search') && $request->search != '') {
        $query->where('hostname', 'like', '%' . $request->search . '%');
    }

    // Logika Pengurutan (Sorting)
    switch ($request->get('sort')) {
        case 'az':
            $query->orderByRaw('LENGTH(hostname) ASC')->orderBy('hostname', 'asc');
            break;
        case 'za':
            $query->orderByRaw('LENGTH(hostname) DESC')->orderBy('hostname', 'desc');
            break;

        case 'oldest':
            $query->orderBy('last_sync', 'asc');
            break;
        case 'newest':
        default:
            $query->orderBy('last_sync', 'desc');
            break;
    }

    // Mengambil data dengan pagination (15 data per halaman)
    // appends(request()->query()) memastikan filter tidak hilang saat pindah halaman
    $devices = $query->paginate(15)->appends($request->query());

    return view('devices.index', compact('devices'));
}
    public function show(Device $device)
    {
        return view('devices.show', compact('device'));
    }

    public function showReport(Request $request, Device $device)
    {
        // 1. Cek apakah path tercatat dan file fisiknya benar-benar ada di server
        if (!$device->battery_report_path || !Storage::disk('public')->exists($device->battery_report_path)) {
            abort(404, 'File laporan fisik tidak ditemukan di server.');
        }

        // 2. Ambil lokasi absolut file di dalam sistem Windows/Linux
        $path = storage_path('app/public/' . $device->battery_report_path);

        // 3. Mode Download
        if ($request->has('download')) {
            return response()->download($path);
        }

        // 4. Mode Tampil di Iframe (BYPASS 403 ERROR DI WINDOWS)
        return response(file_get_contents($path), 200)
            ->header('Content-Type', 'text/html');
    }
    public function export()
    {
        $fileName = 'Laporan_Hardware_System_' . date('Y-m-d_H-i') . '.csv';

        // Ambil semua data perangkat, urutkan dari yang terbaru sinkron
        $devices = Device::orderBy('last_sync', 'desc')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        // Judul kolom CSV yang sudah diperbarui
        $columns = [
            'Hostname',
            'PC Model',
            'CPU Name',
            'IP Address',
            'MAC Address',
            'WiFi SSID',
            'OS Version',
            'User Aktif',
            'Total RAM (GB)',
            'RAM Usage (%)',
            'CPU Usage (%)',
            'Disk C: Free (%)',
            'Disk D: Free (%)',
            'Battery Health (%)',
            'Terakhir Booting',
            'Uptime (Hari)',
            'Terakhir Sinkron'
        ];

        $callback = function () use ($devices, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($devices as $device) {
                fputcsv($file, [
                    $device->hostname,
                    $device->pc_model ?? '-',
                    $device->cpu_name ?? '-',
                    $device->ip_address ?? '-',
                    $device->mac_address ?? '-',
                    $device->wifi_ssid ?? '-',
                    $device->os_version ?? '-',
                    $device->logged_in_user ?? '-',
                    $device->total_ram_gb ?? '-',
                    $device->ram_usage_percent ?? 'N/A',
                    $device->cpu_usage_percent ?? 'N/A',
                    $device->free_disk_c_percent ?? 'N/A',
                    $device->free_disk_d_percent ?? '-',
                    $device->battery_health_percent ?? 'N/A',
                    $device->last_boot_time ? $device->last_boot_time->format('Y-m-d H:i') : '-',
                    $device->uptime_days ?? '-',
                    $device->last_sync ? $device->last_sync->format('Y-m-d H:i') : '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
