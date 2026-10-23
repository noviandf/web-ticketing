<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            // Identitas Utama
            $table->string('hostname')->unique();
            $table->string('serial_number')->nullable();
            $table->string('logged_in_user')->nullable();
            $table->string('os_version')->nullable();
            $table->string('pc_model')->nullable();
            $table->string('cpu_name')->nullable();

            // Jaringan
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('wifi_ssid')->nullable();

            // Metrik Performa
            $table->float('free_disk_c_percent')->nullable();
            $table->float('free_disk_d_percent')->nullable();

            // PASTIKAN BARIS INI HANYA ADA SATU
            $table->float('ram_usage_percent')->nullable();

            $table->float('total_ram_gb')->nullable();
            $table->float('cpu_usage_percent')->nullable();

            // Baterai (Hybrid: Persentase dan File HTML)
            $table->float('battery_health_percent')->nullable();
            $table->string('battery_report_path')->nullable();

            // Waktu & Logging
            $table->timestamp('last_boot_time')->nullable();
            $table->integer('uptime_days')->nullable();
            $table->timestamp('last_sync')->nullable();

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
