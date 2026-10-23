<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'reporter_name',
        'cubicle_number',
        'category_id',
        'device_id',
        'scope',
        'item_type',
        'title',
        'description',
        'priority',
        'status',
        'location',
        'photo',
        'resolution_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function comments()
    {
        // Satu tiket punya BANYAK komentar
        return $this->hasMany(TicketComment::class)->latest(); // Urutkan dari yang terbaru (opsional)
    }
}
