<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Carbon\Carbon;

class AutoCloseTickets extends Command
{
    /**
     * Nama perintah yang akan kita ketik di terminal.
     */
    protected $signature = 'tickets:autoclose';

    /**
     * Penjelasan singkat perintah ini.
     */
    protected $description = 'Otomatis menutup tiket yang sudah Resolved lebih dari 24 jam';

    /**
     * Logika utama robot.
     */
    public function handle()
    {
        $this->info('Sedang memeriksa tiket yang sudah basi...');

        // Cari tiket 'resolved' yang sudah lewat 24 jam dari update terakhir
        $tickets = Ticket::where('status', 'resolved')
            ->where('updated_at', '<', Carbon::now()->subHours(24))
            ->get();

        $count = 0;
        foreach ($tickets as $ticket) {
            $ticket->update(['status' => 'closed']);
            $count++;
            $this->info("Tiket #{$ticket->id} berhasil ditutup.");
        }

        if ($count == 0) {
            $this->info('Tidak ada tiket yang perlu ditutup saat ini.');
        } else {
            $this->info("SELESAI! Total {$count} tiket ditutup otomatis.");
        }
    }
}
