<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket; // <--- Import Model Ticket

class NewTicketCreated extends Notification
{
    use Queueable;

    public $ticket; // Variabel penampung

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket; // Tangkap data tiket
    }
    // 1. Ubah jalur pengiriman
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // Ganti 'mail' jadi 'database'
    }

    // 2. Tentukan data apa yang mau disimpan ke database
    // (Tambahkan fungsi baru ini di bawah)
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'Tiket Baru: ' . $this->ticket->title,
            'message' => $this->ticket->user->name . ' membuat tiket baru.',
            'url' => route('tickets.show', $this->ticket->id),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tiket Baru: ' . $this->ticket->judul) // Sesuaikan variabelmu
            ->line('Ada tiket baru masuk dari user.')
            ->action('Lihat Tiket', url('/tickets/' . $this->ticket->id))
            ->line('Segera diproses ya!');
    }
}
