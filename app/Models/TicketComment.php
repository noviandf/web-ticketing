<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TicketComment extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'user_id', 'message'];

    // Komentar milik User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Komentar milik Tiket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
