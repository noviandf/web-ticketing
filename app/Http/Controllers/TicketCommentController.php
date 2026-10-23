<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramService;

class TicketCommentController extends Controller
{
    public function store(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Pastikan tiketnya ada
        $ticket = Ticket::findOrFail($ticketId);

        // keamanan: Hanya admin atau pembuat tiket yang bisa komentar
        if (Auth::user()->role !== 'admin' && Auth::id() !== $ticket->user_id) {
            abort(403, 'Akses ditolak. Anda tidak berhak mengomentari tiket ini.');
        }

        // Simpan Komentar
        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Opsional: Jika admin yang balas, ubah status tiket jadi 'In Progress' otomatis
        if (Auth::user()->role == 'admin' && $ticket->status == 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        try {
            broadcast(new \App\Events\TicketPushed("Catatan baru pada Tiket '{$ticket->title}' dari " . Auth::user()->name, Auth::id()));
        } catch (\Exception $e) {
            Log::error("Gagal mengirim broadcast Websocket: " . $e->getMessage());
        }

        // Notifikasi Telegram (HANYA JIKA USER/PELAPOR YANG MEMBALAS)
        if (Auth::user()->role !== 'admin') {
            $message = "💬 <b>BALASAN BARU DARI PELAPOR!</b>\n\n";
            $message .= "<b>Tiket:</b> {$ticket->title}\n";
            $message .= "<b>Dari:</b> " . Auth::user()->name . "\n\n";
            $message .= "<b>Pesan:</b>\n<i>\"{$request->message}\"</i>\n\n";
            $message .= "Silakan cek di Dashboard Ticketing.";

            TelegramService::sendMessage($message);
        }

        return redirect()->back()->with('success', 'Pesan terkirim.');
    }
}
