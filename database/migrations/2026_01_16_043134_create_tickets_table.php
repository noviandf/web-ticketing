<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            
            // Pelapor Masalah
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Jenis Masalah (Nyambung ke tabel categories)
            // Kategori sudah aman dipanggil karena tanggal file ini 2026 (lebih baru dari kategori 2025)
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            
            // Ditugaskan ke siapa
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            // Data Utama
            $table->string('title');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->string('location')->nullable(); 
            
            // Status Lengkap
            $table->enum('status', ['open', 'pending', 'on_hold', 'resolved', 'closed', 'cancelled'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('low');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};