<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->id();
            // Relasi ke Tiket (Jika tiket dihapus, komentar ikut hilang)
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');

            // Relasi ke User (Siapa yang nulis)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->text('message'); // Isi pesan
            $table->timestamps(); // Mencatat created_at (kapan komentar dibuat)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_comments');
    }
};
