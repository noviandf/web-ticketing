<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Ubah tipe kolom 'status' dari ENUM menjadi STRING (Varchar 255)
            // Agar bisa menampung 'in_progress', 'cancelled', dll.
            $table->string('status')->default('open')->change();
        });
    }

    public function down(): void
    {
        // Kembalikan ke ENUM jika di-rollback (Opsional, sesuaikan dengan migrasi awalmu)
        Schema::table('tickets', function (Blueprint $table) {
            // $table->enum('status', ['open', 'pending', 'resolved', 'closed'])->default('open')->change();
        });
    }
};
