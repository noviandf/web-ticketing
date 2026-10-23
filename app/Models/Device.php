<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Mengonversi kolom waktu menjadi objek datetime bawaan Carbon Laravel
    protected function casts(): array
    {
        return [
            'last_boot_time' => 'datetime',
            'last_sync' => 'datetime',
        ];
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class)->latest();
    }
}
