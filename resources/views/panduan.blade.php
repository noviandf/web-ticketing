<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panduan Penggunaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2 dark:border-gray-700">Pusat Bantuan</h3>
                    <p class="mb-6 text-gray-600 dark:text-gray-400">Selamat datang di panduan interaktif TGA Ticketing System. Silakan lihat animasi di bawah ini jika Anda lupa cara melakukan sesuatu.</p>

                    <div class="space-y-12">
                        <!-- Skenario 1 -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                            <h4 class="text-md font-semibold text-blue-600 dark:text-blue-400 mb-2">1. Pengisian Form Tiket Baru</h4>
                            <p class="text-sm mb-4">Untuk membuat tiket baru, klik tombol <b>Buat Tiket</b>, isi form dengan lengkap (termasuk Nama Pelapor dan Kubikel), lalu submit. Setelah berhasil, klik tombol Detail pada daftar tiket untuk memantau tiket Anda.</p>
                            
                            <div class="bg-white dark:bg-gray-900 w-full md:w-3/4 p-2 rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm">
                                <img src="{{ asset('gif/buattiket.gif') }}" alt="Panduan Buat Tiket" class="w-full h-auto rounded">
                            </div>
                        </div>

                        <!-- Skenario 2 -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                            <h4 class="text-md font-semibold text-blue-600 dark:text-blue-400 mb-2">2. Cek Update / Notifikasi Tiket</h4>
                            <p class="text-sm mb-4">Ketika ada pembaruan status atau balasan dari teknisi, Anda akan menerima Notifikasi (Pop-up & Suara). Anda dapat <b>me-refresh halaman web secara manual</b> atau <b>meng-klik notifikasi tersebut</b> agar data terbaru muncul, lalu masuk ke Detail Tiket untuk melihat updatenya.</p>
                            
                            <div class="bg-white dark:bg-gray-900 w-full md:w-3/4 p-2 rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm">
                                <img src="{{ asset('gif/cekupdatetiket.gif') }}" alt="Panduan Cek Update Tiket" class="w-full h-auto rounded">
                            </div>
                        </div>

                        <!-- Skenario 3 -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                            <h4 class="text-md font-semibold text-blue-600 dark:text-blue-400 mb-2">3. Chatting & Berdiskusi dalam Tiket</h4>
                            <p class="text-sm mb-4">Masuk ke halaman Detail Tiket lalu scroll ke bagian bawah untuk melihat riwayat obrolan. Anda bisa membalas chat dari teknisi atau memberikan informasi tambahan terkait kendala yang dialami.</p>
                            
                            <div class="bg-white dark:bg-gray-900 w-full md:w-3/4 p-2 rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm">
                                <img src="{{ asset('gif/chattiket.gif') }}" alt="Panduan Chat Tiket" class="w-full h-auto rounded">
                            </div>
                        </div>

                        <!-- Skenario 4 -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                            <h4 class="text-md font-semibold text-blue-600 dark:text-blue-400 mb-2">4. Reopen (Buka Ulang) Tiket</h4>
                            <p class="text-sm mb-4">Jika tiket Anda sudah ditutup oleh teknisi (Resolved/Closed) namun masalah belum tuntas, Anda bisa mengkonfirmasi hal tersebut melalui kolom chat lalu klik tombol <b>Reopen Tiket</b> agar tiket kembali berstatus Open (Batas waktu maksimal 24 jam).</p>
                            
                            <div class="bg-white dark:bg-gray-900 w-full md:w-3/4 p-2 rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm">
                                <img src="{{ asset('gif/reopentiket.gif') }}" alt="Panduan Reopen Tiket" class="w-full h-auto rounded">
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</x-app-layout>
