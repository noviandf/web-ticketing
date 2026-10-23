<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketPushed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message; // Variabel yang akan dikirim ke browser
    public $user_id;

    public function __construct($message, $user_id = null)
    {
        $this->message = $message;
        $this->user_id = $user_id;
    }

    // Menentukan di frekuensi mana pesan ini disiarkan
    public function broadcastOn(): array
    {
        return [
            new Channel('public-notifications'),
        ];
    }

    // (Opsional) Menentukan nama event yang dibaca oleh Javascript
    public function broadcastAs(): string
    {
        return 'TicketEvent';
    }
}