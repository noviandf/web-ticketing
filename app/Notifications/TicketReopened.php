<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketReopened extends Notification
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    // 1. Tentukan Jalur Pengiriman (Email DAN Database)
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    // 2. ISIAN EMAIL (Ini yang tadi hilang!)
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Tiket Reopen: ' . $this->ticket->title)
            ->greeting('Halo Admin!')
            ->line('User ' . $this->ticket->user->name . ' telah membuka kembali (Reopen) tiket #' . $this->ticket->id)
            ->line('Mohon dicek kembali kenapa masalah belum tuntas.')
            ->action('Lihat Tiket', route('tickets.show', $this->ticket->id));
    }

    // 3. ISIAN LONCENG / DATABASE
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'Tiket Reopen: ' . $this->ticket->title,
            'message' => $this->ticket->user->name . ' membuka kembali tiket #' . $this->ticket->id,
            'url' => route('tickets.show', $this->ticket->id),
        ];
    }
}
