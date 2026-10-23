<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    /**
     * Relasi: Satu Kategori memiliki BANYAK Tiket.
     * Fungsi ini wajib ada agar withCount('tickets') di dashboard bisa jalan.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}