<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
                {{ __('Detail Tiket') }} #{{ $ticket->id }}
            </h2>
            <a href="{{ route('tickets.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-sm font-bold transition-colors">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- KOLOM KIRI: DETAIL TIKET & DISKUSI -->
                <div class="md:col-span-2 space-y-6">

                    <!-- 1. KOTAK INFORMASI UTAMA -->
                    <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg transition-colors duration-300">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 border-b dark:border-gray-700 pb-2">
                            {{ $ticket->title }}
                        </h3>

                        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400">Akun Login:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-200">{{ $ticket->user->name }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400">Nama Pelapor:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-200">{{ $ticket->reporter_name ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400">No. Cubicle / Meja:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-200">{{ $ticket->cubicle_number ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400">Type Kendala / Item:</span>
                                <span class="font-semibold px-2 py-0.5 rounded text-xs border bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                    {{ $ticket->item_type ?? '-' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400">Kategori:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-200">{{ $ticket->category->name ?? 'Umum' }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400">Status:</span>
                                <span class="px-2 py-1 text-xs font-bold rounded 
                                    {{ $ticket->status == 'open' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 
                                      ($ticket->status == 'resolved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                      'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                    {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400">Prioritas:</span>
                                <span class="font-semibold uppercase {{ $ticket->priority == 'high' ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-200' }}">
                                    {{ $ticket->priority }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400">Lokasi:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-200">{{ $ticket->location }}</span>
                            </div>
                        </div>

                        <!-- KOTAK DESKRIPSI -->
                        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg text-gray-700 dark:text-gray-300 whitespace-pre-line border border-gray-200 dark:border-gray-700 transition-colors">
                            {{ $ticket->description }}
                        </div>

                        @if($ticket->photo)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Foto Bukti:</p>
                            <img src="{{ asset('storage/' . $ticket->photo) }}" class="max-h-64 rounded border dark:border-gray-700">
                        </div>
                        @endif
                    </div>

                    <!-- 2. CATATAN SOLUSI DARI IT -->
                    @if($ticket->resolution_notes)
                    <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 p-6 shadow sm:rounded-lg transition-colors">
                        <h3 class="text-lg font-bold text-blue-900 dark:text-blue-300 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Catatan IT / Solusi
                        </h3>
                        <div class="text-blue-800 dark:text-blue-200 whitespace-pre-line text-sm">
                            {{ $ticket->resolution_notes }}
                        </div>
                        <p class="text-xs text-blue-500 dark:text-blue-400 mt-2 italic">Diupdate oleh Admin pada {{ $ticket->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif

                    <!-- LOGIKA REOPEN -->
                    @if($ticket->status == 'resolved')
                    @php
                    $batasWaktu = $ticket->updated_at->addHours(24);
                    $sekarang = \Carbon\Carbon::now();
                    @endphp
                    @if($sekarang->lessThan($batasWaktu))
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg shadow-sm transition-colors">
                        <h4 class="font-bold text-yellow-800 dark:text-yellow-400 mb-1">Masalah belum tuntas?</h4>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-3">
                            Jika solusi teknisi tidak berhasil, Anda bisa membuka tiket ini kembali sebelum <b>{{ $batasWaktu->format('d M H:i') }}</b>.
                        </p>
                        @if(auth()->user()->role == 'admin' || auth()->id() == $ticket->user_id)
                        <form action="{{ route('tickets.reopen', $ticket) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('Yakin ingin membuka tiket ini lagi?')" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded shadow text-sm transition">
                                Buka Kembali Tiket (Reopen)
                            </button>
                        </form>
                        @else
                        <div class="bg-yellow-100 dark:bg-yellow-800/50 text-yellow-800 dark:text-yellow-200 p-3 rounded text-sm border border-yellow-200 dark:border-yellow-700">
                            Hanya Pemilik Tiket yang bisa melakukan Reopen.
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="p-4 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg opacity-75 transition-colors">
                        <h4 class="font-bold text-gray-700 dark:text-gray-300">Tiket Ditutup Otomatis</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            Batas waktu konfirmasi (1x24 Jam) telah habis. Jika masalah muncul lagi, silakan buat tiket baru.
                        </p>
                        <a href="{{ route('tickets.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                            + Buat Tiket Baru
                        </a>
                    </div>
                    @endif
                    @endif

                    <!-- 3. KOTAK DISKUSI / CHAT -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center gap-2 border-b dark:border-gray-700 pb-4">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                </svg>
                                Diskusi / Update Progress
                            </h3>

                            <div class="space-y-6 mb-8 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                                @forelse($ticket->comments as $comment)
                                <div class="flex gap-4 {{ $comment->user_id == Auth::id() ? 'flex-row-reverse' : '' }}">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shadow
                                                {{ $comment->user->role == 'admin' ? 'bg-red-600' : 'bg-blue-500' }}">
                                            {{ substr($comment->user->name, 0, 2) }}
                                        </div>
                                    </div>

                                    <!-- Bubble Chat -->
                                    <div class="max-w-[85%]">
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1 {{ $comment->user_id == Auth::id() ? 'text-right' : '' }}">
                                            <span class="font-bold text-gray-700 dark:text-gray-300">{{ $comment->user->name }}</span>
                                            @if($comment->user->role == 'admin')
                                            <span class="bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-400 text-[10px] px-1 rounded ml-1 border border-red-200 dark:border-red-800">Admin</span>
                                            @endif
                                            &bull; {{ $comment->created_at->diffForHumans() }}
                                        </div>
                                        <div class="p-3 rounded-lg shadow-sm text-sm leading-relaxed border
                                                {{ $comment->user_id == Auth::id() 
                                                     ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-900 dark:text-blue-100 border-blue-100 dark:border-blue-800 rounded-tr-none' 
                                                     : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-600 rounded-tl-none' }}">
                                            {!! nl2br(e($comment->message)) !!}
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-600 transition-colors">
                                    <p class="text-gray-400 dark:text-gray-500 italic text-sm">Belum ada diskusi di tiket ini.</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">Mulai percakapan untuk menanyakan update atau memberi info tambahan.</p>
                                </div>
                                @endforelse
                            </div>

                            @if($ticket->status != 'closed' && $ticket->status != 'cancelled')
                            @if(auth()->user()->role == 'admin' || auth()->id() == $ticket->user_id)
                            <form action="{{ route('comments.store', $ticket->id) }}" method="POST" class="relative">
                                @csrf
                                <label for="message" class="sr-only">Tulis pesan...</label>
                                <textarea
                                    name="message"
                                    id="message"
                                    rows="3"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 transition-colors"
                                    placeholder="Tulis balasan, update progress, atau pertanyaan di sini..."></textarea>
                                <div class="mt-3 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Kirim Pesan
                                    </button>
                                </div>
                            </form>
                            @else
                            <div class="bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 p-4 rounded text-center transition-colors">
                                <p class="text-gray-500 dark:text-gray-400 text-sm italic">
                                    🔒 Anda tidak memiliki akses untuk membalas diskusi di tiket ini.
                                </p>
                            </div>
                            @endif
                            @else
                            <div class="bg-gray-100 dark:bg-gray-700/50 border-l-4 border-gray-400 dark:border-gray-500 p-4 rounded-r transition-colors">
                                <div class="flex">
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            Tiket ini sudah <b>{{ strtoupper($ticket->status) }}</b>. Kolom diskusi telah ditutup.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: PANEL KONTROL ADMIN -->
                <div class="md:col-span-1">
                    @if(auth()->user()->role == 'admin')
                    <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg h-fit border-l-4 border-indigo-500 sticky top-6 transition-colors duration-300">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Panel Kontrol</h3>

                        @if ($errors->any())
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                <strong>Gagal menyimpan!</strong>
                                <ul class="list-disc mt-1 ml-4 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Update Status</label>
                                <select name="status" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="cancelled" {{ $ticket->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioritas</label>
                                <select name="priority" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                    <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori Kendala</label>
                                <select name="category_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $ticket->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lingkup (SLA)</label>
                                <select name="scope" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                    <option value="internal" {{ $ticket->scope == 'internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="eksternal" {{ $ticket->scope == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Perangkat Terkait</label>
                                <select name="device_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                    <option value="">-- Tidak ada perangkat --</option>
                                    @foreach($devices as $device)
                                        <option value="{{ $device->id }}" {{ $ticket->device_id == $device->id ? 'selected' : '' }}>{{ $device->hostname }} ({{ $device->ip_address }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan Resmi (Solusi Final)</label>
                                <textarea name="resolution_notes" rows="4" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition-colors" placeholder="Tulis solusi final di sini...">{{ $ticket->resolution_notes }}</textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gunakan kolom diskusi untuk update harian. Gunakan ini untuk kesimpulan akhir.</p>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>