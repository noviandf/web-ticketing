<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan Facade ini ada
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewTicketCreated;
use App\Notifications\TicketReopened;
use App\Events\TicketPushed;
use App\Services\TelegramService;

class TicketController extends Controller
{
    // 1. LIST TIKET
    public function index(Request $request)
    {
        // 2. CEK ROLE USER (FILTER OTOMATIS)
        $user = Auth::user();
        $query = Ticket::with(['user', 'category']);

        if ($user->role == 'admin') {
            // A. Jika Admin/Management: TAMPILKAN SEMUA (Tidak perlu di-filter user_id)
            // Mereka bisa melihat keluhan seluruh kantor.
        } else {
            // B. Jika User Biasa (Agent): FILTER HANYA TIKET SAYA
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('tickets.index', compact('tickets', 'categories'));
    }

    // 2. FORM CREATE
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('tickets.create', compact('categories'));
    }

    // 3. STORE (SIMPAN & KIRIM NOTIF)
    public function store(Request $request)
    {
        $request->validate([
            'reporter_name' => 'required|string|max:255',
            'cubicle_number' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'item_type' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high',
            'location' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('ticket-photos', 'public');
        }

        $category = Category::find($request->category_id);
        $isExternal = $category && (str_contains(strtolower($category->name), 'internet') || str_contains(strtolower($category->name), 'jaringan'));

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'reporter_name' => $request->reporter_name,
            'cubicle_number' => $request->cubicle_number,
            'category_id' => $request->category_id,
            'device_id' => $request->device_id ?? null,
            'scope' => $isExternal ? 'eksternal' : 'internal',
            'item_type' => $request->item_type,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'location' => $request->location,
            'photo' => $photoPath,
            'status' => 'open',
        ]);

        // --- NOTIFIKASI ---
        $admins = User::where('role', 'admin')->get();

        // 1. Internal Laravel Notification
        // Pastikan class NewTicketCreated ada (php artisan make:notification NewTicketCreated)
        // Notification::send($admins, new NewTicketCreated($ticket));
        try {
            broadcast(new TicketPushed("Tiket Baru dari " . auth()->user()->name . ": " . $ticket->title, Auth::id()));
        } catch (\Exception $e) {
            Log::error("Gagal mengirim broadcast Websocket: " . $e->getMessage());
        }

        // 2. Telegram Notification
        $priorityIcon = $ticket->priority == 'high' || $ticket->priority == 'critical' ? '🚨' : '🎫';
        $message = "{$priorityIcon} <b>TIKET BARU MASUK!</b>\n\n";
        $message .= "<b>Pelapor:</b> {$ticket->reporter_name}\n";
        $message .= "<b>Judul:</b> {$ticket->title}\n";
        $message .= "<b>Prioritas:</b> " . strtoupper($ticket->priority) . "\n";
        $message .= "<b>Kategori:</b> " . ($ticket->category->name ?? '-') . "\n\n";
        $message .= "Silakan cek di Dashboard Ticketing.";
        
        TelegramService::sendMessage($message);

    return redirect()->route('tickets.index')->with('success', 'Tiket berhasil dibuat.');
    }

    // 4. SHOW
    public function show(Ticket $ticket)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $ticket->user_id) {
            abort(403, 'Akses Ditolak. Anda tidak berhak melihat tiket ini.');
        }

        // EAGER LOADING: Mencegah N+1 Query dengan meload data berelasi sekaligus
        $ticket->load(['user', 'category', 'device', 'comments.user']);
        $categories = \App\Models\Category::all();
        $devices = \App\Models\Device::all();

        return view('tickets.show', compact('ticket', 'categories', 'devices'));
    }

    // 5. UPDATE
    public function update(Request $request, Ticket $ticket)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $ticket->user_id) {
            abort(403, 'Akses Ditolak. Anda tidak berhak mengubah tiket ini.');
        }

        $rules = [
            'status' => 'required|string',
            'priority' => 'required|string',
            'resolution_notes' => 'nullable|string', 
        ];

        if (in_array(Auth::user()->role, ['admin', 'agent', 'tl'])) {
            if ($request->has('category_id')) $rules['category_id'] = 'required|exists:categories,id';
            if ($request->has('device_id')) $rules['device_id'] = 'nullable|exists:devices,id';
            if ($request->has('scope')) $rules['scope'] = 'required|in:internal,eksternal';
        }

        $request->validate($rules);
        // Log::info('Validation passed.', $request->all());

        $oldStatus = $ticket->status;
        $newStatus = $request->status;

        $updateData = [
            'status' => $newStatus,
            'priority' => $request->priority,
            'resolution_notes' => $request->resolution_notes ?? $ticket->resolution_notes,
        ];

        if (in_array(Auth::user()->role, ['admin', 'agent', 'tl'])) {
            if ($request->has('category_id')) $updateData['category_id'] = $request->category_id;
            if ($request->has('device_id')) $updateData['device_id'] = $request->device_id;
            if ($request->has('scope')) $updateData['scope'] = $request->scope;
        }

        // Jika status baru adalah resolved dan status lama BUKAN resolved, catat waktu resolved-nya
        if ($newStatus == 'resolved' && strtolower($oldStatus) != 'resolved') {
            $updateData['resolved_at'] = now();
        }

        $ticket->update($updateData);

        // Notif Reopen Internal
        $admins = User::where('role', 'admin')->get();
        if ($newStatus == 'open' && (strtolower($oldStatus) == 'resolved' || strtolower($oldStatus) == 'closed')) {
            // Notification::send($admins, new TicketReopened($ticket));
        }

        try {
            broadcast(new TicketPushed("Tiket '{$ticket->title}' telah diperbarui oleh " . Auth::user()->name, Auth::id()));
        } catch (\Exception $e) {
            Log::error("Gagal mengirim broadcast Websocket: " . $e->getMessage());
        }

        // (Tidak ada notifikasi telegram untuk perubahan status sesuai prinsip anti-spam)

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui!');
    }

    // 6. EXPORT CSV / PDF
    public function export(Request $request)
    {
        $query = Ticket::with(['user', 'category']);

        // Filter sama seperti dashboard
        if ($request->filled('filter_month')) {
            $query->where('created_at', 'like', $request->filter_month . '%');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        if ($request->filled('status')) $query->where('status', $request->status);

        $tickets = $query->latest()->get();

        $format = $request->get('format', 'csv');

        if ($format == 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tickets.pdf', compact('tickets'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download('Laporan_Tiket_' . date('Y-m-d_H-i') . '.pdf');
        }

        // Jika CSV
        $fileName = 'Laporan_Tiket_' . date('Y-m-d_H-i') . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Jam Masuk', 'Jam Selesai', 'Durasi Penyelesaian', 'Pelapor', 'Type', 'Judul', 'Kategori', 'Lingkup', 'Prioritas', 'Status', 'Solusi'];

        $callback = function () use ($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $ticket) {
                $lingkup = $ticket->scope == 'eksternal' ? 'Eksternal' : 'Internal (' . $ticket->location . ')';
                
                $jamMasuk = $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '-';
                $jamSelesai = $ticket->resolved_at ? $ticket->resolved_at->format('d/m/Y H:i') : (in_array($ticket->status, ['closed', 'resolved']) ? $ticket->updated_at->format('d/m/Y H:i') : '-');

                $durasi = '-';
                if ($jamSelesai !== '-') {
                    $end_time = $ticket->resolved_at ?? $ticket->updated_at;
                    $minutes = $ticket->created_at->diffInMinutes($end_time);
                    if ($minutes == 0) {
                        $durasi = "0 Menit";
                    } else {
                        $h = floor($minutes / 60);
                        $m = $minutes % 60;
                        $durasi = "{$h} Jam {$m} Menit";
                    }
                }

                fputcsv($file, [
                    $ticket->id,
                    $jamMasuk,
                    $jamSelesai,
                    $durasi,
                    $ticket->user->name,
                    $ticket->item_type ?? '-',
                    $ticket->title,
                    $ticket->category->name ?? '-',
                    $lingkup,
                    strtoupper($ticket->priority),
                    strtoupper($ticket->status),
                    $ticket->resolution_notes ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 7. REOPEN
    public function reopen(Ticket $ticket)
    {
        $isOwner = (Auth::id() == $ticket->user_id);
        $isAdmin = (Auth::user()->role == 'admin');

        if (! $isOwner && ! $isAdmin) {
            abort(403, 'Akses Ditolak.');
        }

        if ($ticket->updated_at->diffInHours(now()) > 24) {
            return back()->with('error', 'Batas waktu reopen habis. Silakan buat tiket baru.');
        }

        $ticket->update(['status' => 'open']);

        // Telegram Notification
        $message = "🔄 <b>TIKET DI-REOPEN!</b>\n\n";
        $message .= "<b>Tiket:</b> {$ticket->title}\n";
        $message .= "<b>Oleh:</b> " . Auth::user()->name . "\n\n";
        $message .= "Tiket ini telah dibuka kembali oleh pelapor. Mohon dicek kembali.";
        
        TelegramService::sendMessage($message);

        return back()->with('success', 'Tiket dibuka kembali.');
    }

    // 8. HAPUS TIKET
    public function destroy(Ticket $ticket)
    {
        // Gunakan Auth::user() biar VS Code tidak merah
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus tiket ini.');
        }

        // Hapus file foto jika ada (biar server gak penuh sampah)
        if ($ticket->photo && Storage::disk('public')->exists($ticket->photo)) {
            Storage::disk('public')->delete($ticket->photo);
        }

        $ticket->delete();

        return redirect()->back()->with('success', 'Tiket berhasil dihapus permanen.');
    }
}
