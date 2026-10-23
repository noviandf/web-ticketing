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
        Schema::table('tickets', function (Blueprint $table) {
            $table->timestamp('resolved_at')->nullable()->after('updated_at');
        });

        // Perbaiki data historis:
        // Jika tiket sudah resolved, anggap resolved_at = updated_at
        \Illuminate\Support\Facades\DB::statement("UPDATE tickets SET resolved_at = updated_at WHERE status = 'resolved'");
        
        // Jika tiket sudah closed, otomatis waktu closed adalah 24 jam setelah resolved, 
        // jadi kita kurangi 24 jam dari updated_at
        \Illuminate\Support\Facades\DB::statement("UPDATE tickets SET resolved_at = DATE_SUB(updated_at, INTERVAL 24 HOUR) WHERE status = 'closed'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('resolved_at');
        });
    }
};
