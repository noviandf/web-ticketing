<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ Auth::user()->role == 'admin' ? __('Dashboard Admin') : __('Tiket Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'management')

            <div class="mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200">Ringkasan Statistik</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <div class="bg-white dark:bg-gray-800 p-4 shadow-sm rounded-lg border-l-4 border-blue-600 dark:border-blue-500">
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Total</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalTickets }}</div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-4 shadow-sm rounded-lg border-l-4 border-red-500 dark:border-red-400">
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Open</div>
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $openTickets }}</div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-4 shadow-sm rounded-lg border-l-4 border-blue-400 dark:border-blue-300">
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">In Progress</div>
                        <div class="text-2xl font-bold text-blue-500 dark:text-blue-400">{{ $inProgressTickets }}</div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-4 shadow-sm rounded-lg border-l-4 border-yellow-500 dark:border-yellow-400">
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Pending</div>
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingTickets }}</div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-4 shadow-sm rounded-lg border-l-4 border-green-500 dark:border-green-400">
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Resolved</div>
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $resolvedTickets }}</div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-4 shadow-sm rounded-lg border-l-4 border-gray-600 dark:border-gray-500">
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Closed</div>
                        <div class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ $closedTickets }}</div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-4 shadow-sm rounded-lg border-l-4 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800/50">
                        <div class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">Cancelled</div>
                        <div class="text-2xl font-bold text-gray-400 dark:text-gray-500">{{ $cancelledTickets }}</div>
                    </div>

                    <div class="bg-indigo-50 dark:bg-indigo-900/40 p-4 shadow-sm rounded-lg border-l-4 border-indigo-600 dark:border-indigo-500 row-span-2">
                        <div class="text-xs font-bold text-indigo-800 dark:text-indigo-300 uppercase mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            SLA Penyelesaian Tiket
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-sm border-b border-indigo-100 dark:border-indigo-800 pb-1">
                                <span class="font-semibold text-indigo-700 dark:text-indigo-400">Keseluruhan</span>
                                <span class="font-bold text-indigo-900 dark:text-indigo-100">{{ $slaAverage }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-b border-indigo-100 dark:border-indigo-800 pb-1">
                                <span class="font-semibold text-teal-700 dark:text-teal-400">Internal</span>
                                <span class="font-bold text-teal-900 dark:text-teal-100">{{ $slaInternal }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-semibold text-orange-700 dark:text-orange-400">Eksternal (ISP)</span>
                                <span class="font-bold text-orange-900 dark:text-orange-100">{{ $slaExternal }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 lg:col-span-2">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                        <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200">Statistik Keluhan</h3>
                        <form action="{{ route('dashboard') }}" method="GET">
                            @if(request('filter_month'))
                                <input type="hidden" name="filter_month" value="{{ request('filter_month') }}">
                            @endif
                            <select name="chart_type" onchange="this.form.submit()" class="text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1 pl-2 pr-8">
                                <option value="type" {{ request('chart_type') == 'type' || !request('chart_type') ? 'selected' : '' }}>Filter: Tipe Kendala (Spesifik)</option>
                                <option value="category" {{ request('chart_type') == 'category' ? 'selected' : '' }}>Filter: Kategori Besar (Umum)</option>
                                <option value="priority" {{ request('chart_type') == 'priority' ? 'selected' : '' }}>Filter: Tingkat Prioritas</option>
                                <option value="lingkup" {{ request('chart_type') == 'lingkup' ? 'selected' : '' }}>Filter: Lingkup (Internal/Eksternal)</option>
                            </select>
                        </form>
                    </div>
                    <div class="h-64 overflow-hidden">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/30 p-6 shadow-sm rounded-lg border border-blue-100 dark:border-blue-800">
                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-300 mb-2">💡 Insight IT</h3>
                    <p class="text-sm text-blue-800 dark:text-blue-200 mb-4">
                        Grafik ini menunjukkan kategori perangkat/layanan apa yang paling sering dilaporkan rusak.
                    </p>
                    <div class="text-sm text-blue-800 dark:text-blue-200">
                        <ul class="list-disc list-inside space-y-2">
                            <li>Kategori tertinggi perlu <b class="dark:text-white">maintenance rutin</b>.</li>
                            <li>Jika <b class="dark:text-white">Hardware</b> tinggi, pertimbangkan peremajaan aset.</li>
                            <li>Jika <b class="dark:text-white">Network</b> tinggi, evaluasi ISP.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="chart-data"
                data-labels="{{ json_encode($chartLabels) }}"
                data-values="{{ json_encode($chartValues) }}"
                class="hidden">
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('categoryChart');
                    const dataElement = document.getElementById('chart-data');

                    // Menyesuaikan warna teks chart dengan tema (deteksi manual class dark di HTML)
                    const isDarkMode = document.documentElement.classList.contains('dark');
                    const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                    const textColor = isDarkMode ? '#9ca3af' : '#6b7280'; // gray-400 vs gray-500

                    if (dataElement && ctx) {
                        const labels = JSON.parse(dataElement.getAttribute('data-labels'));
                        const data = JSON.parse(dataElement.getAttribute('data-values'));

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Jumlah Tiket',
                                    data: data,
                                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                                    borderColor: 'rgb(59, 130, 246)',
                                    borderWidth: 1,
                                    borderRadius: 4,
                                    barPercentage: 0.5
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        grid: {
                                            color: gridColor
                                        },
                                        ticks: {
                                            color: textColor
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: gridColor
                                        },
                                        ticks: {
                                            stepSize: 1,
                                            color: textColor
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
            @endif


            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                            {{ Auth::user()->role == 'admin' ? 'Laporan Tiket Bulanan' : 'Tiket Saya' }}
                        </h3>

                        <div class="flex flex-col items-end gap-1">
                            <div class="flex gap-2">
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'management')
                                <div>
                                    <a href="{{ route('tickets.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center text-sm transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Excel/CSV
                                    </a>
                                    <a href="{{ route('tickets.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center text-sm transition ml-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        PDF
                                    </a>
                                </div>
                                @endif

                                <a href="{{ route('tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow text-sm transition">
                                    + Buat Tiket
                                </a>
                            </div>
                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'management')
                                <div class="text-[11px] text-gray-500 dark:text-gray-400 italic">
                                    *Data export otomatis mengikuti filter Periode & Status yang Anda pilih.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                        <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            
                            <div class="col-span-1 md:col-span-1">
                                <input type="month" name="filter_month" value="{{ request('filter_month') }}" title="Filter Berdasarkan Bulan" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Masalah / Pelapor..." class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <select name="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm text-sm">
                                    <option value="">- Semua Status -</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div class="col-span-1 flex gap-2">
                                <button type="submit" class="w-full bg-gray-800 dark:bg-gray-700 hover:bg-gray-900 dark:hover:bg-gray-600 text-white font-bold py-2 px-2 rounded text-sm transition">Filter</button>
                                @if(request('filter_month') || request('search') || request('status'))
                                    <a href="{{ route('dashboard') }}" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-3 rounded text-sm transition flex items-center justify-center" title="Reset Semua Filter">✕</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm">
                        <table class="min-w-full bg-white dark:bg-gray-800 text-sm">
                            <thead class="bg-blue-600 dark:bg-blue-800 text-white sticky top-0 z-10">
                                <tr>
                                    <th class="py-3 px-4 border-r border-white-500 dark:border-gray-700 text-left font-bold uppercase tracking-wider w-1/4">Jenis Kendala</th>
                                    <th class="py-3 px-4 border-r border-white-500 dark:border-gray-700 text-center font-bold uppercase tracking-wider">Dibuat</th>
                                    <th class="py-3 px-4 border-r border-white-500 dark:border-gray-700 text-center font-bold uppercase tracking-wider">Type Kendala</th>
                                    <th class="py-3 px-4 border-r border-white-500 dark:border-gray-700 text-center font-bold uppercase tracking-wider">Kategori</th>
                                    <th class="py-3 px-4 border-r border-white-500 dark:border-gray-700 text-center font-bold uppercase tracking-wider">Lingkup</th>
                                    <th class="py-3 px-4 border-r border-white-500 dark:border-gray-700 text-center font-bold uppercase tracking-wider">Priority</th>
                                    <th class="py-3 px-4 border-r border-white-500 dark:border-gray-700 text-center font-bold uppercase tracking-wider">Status</th>
                                    <th class="py-3 px-4 text-center font-bold uppercase tracking-wider w-24">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($tickets as $ticket)
                                @php
                                    $isSlaBreach = in_array($ticket->status, ['open', 'in_progress']) && $ticket->created_at->diffInHours(now()) >= 2;
                                @endphp
                                <tr class="{{ $isSlaBreach ? 'bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 hover:bg-red-100 dark:hover:bg-red-900/40' : 'hover:bg-yellow-50 dark:hover:bg-gray-700' }} transition duration-150 ease-in-out group">

                                    {{-- 1. JUDUL & USER --}}
                                    <td class="py-3 px-4 border-r border-gray-200 dark:border-gray-700 align-top">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="font-bold text-gray-800 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 hover:underline block mb-1">
                                            {{ Str::limit($ticket->title, 50) }}
                                        </a>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center flex-wrap">
                                            <span class="font-semibold mr-1">Oleh:</span> {{ $ticket->user->name }}
                                            <span class="mx-1">•</span> #{{ $ticket->id }}
                                            @if($isSlaBreach)
                                                <span class="ml-2 text-[10px] font-bold text-red-600 dark:text-red-300 bg-red-100 dark:bg-red-800 px-1 rounded animate-pulse">SLA BREACH</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- 2. DIBUAT (Tanggal dan Jam ditumpuk presisi) --}}
                                    <td class="py-3 px-4 border-r border-gray-200 dark:border-gray-700 text-center align-middle whitespace-nowrap">
                                        <div class="text-[13px] font-bold text-gray-700 dark:text-gray-300">
                                            {{ $ticket->created_at->format('d M Y') }}
                                        </div>
                                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $ticket->created_at->format('H:i') }} WIB
                                        </div>
                                    </td>

                                    {{-- 3. TYPE ITEM (Badge Warna) --}}
                                    <td class="py-3 px-4 border-r border-gray-200 dark:border-gray-700 text-center align-middle">
                                        @php
                                        $type = $ticket->item_type;
                                        $colorBadge = 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200';
                                        if(in_array($type, ['Printer', 'TV Wallboard Mati', 'Kelistrikan', 'Scanner'])) {
                                        $colorBadge = 'bg-yellow-800 text-white';
                                        } elseif(in_array($type, ['CMS/Microsip', 'Mcsys', 'Email Penjaminan', 'Server / System Down'])) {
                                        $colorBadge = 'bg-red-700 text-white';
                                        } elseif(in_array($type, ['Mouse', 'Keyboard', 'Headset Agent', 'Webcam'])) {
                                        $colorBadge = 'bg-yellow-200 text-yellow-800 border border-yellow-300 dark:bg-yellow-900/40 dark:text-yellow-400 dark:border-yellow-700';
                                        } elseif(in_array($type, ['Laptop', 'OS/Windows', 'Monitor'])) {
                                        $colorBadge = 'bg-purple-100 text-purple-800 border border-purple-200 dark:bg-purple-900/40 dark:text-purple-400 dark:border-purple-700';
                                        } elseif(str_contains($type, 'Internet') || str_contains($type, 'LAN') || str_contains($type, 'VoIP')) {
                                        $colorBadge = 'bg-cyan-100 text-cyan-800 border border-cyan-200 dark:bg-cyan-900/40 dark:text-cyan-400 dark:border-cyan-700';
                                        }
                                        @endphp
                                        <span class="px-2 py-1 rounded shadow-sm text-xs font-bold whitespace-nowrap {{ $colorBadge }}">
                                            {{ $type ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- 4. KATEGORI --}}
                                    <td class="py-3 px-4 border-r border-gray-200 dark:border-gray-700 text-center align-middle">
                                        <span class="font-semibold text-gray-700 dark:text-gray-300 text-xs uppercase">
                                            {{ trim(Str::before($ticket->category->name ?? '-', '(')) }}
                                        </span>
                                    </td>

                                    {{-- 5. LINGKUP (Internal/Eksternal) --}}
                                    <td class="py-3 px-4 border-r border-gray-200 dark:border-gray-700 text-center align-middle">
                                        @php
                                        $isExternal = $ticket->scope === 'eksternal';
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $isExternal ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-400' : 'bg-blue-50 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400' }}">
                                            {{ $isExternal ? 'Eksternal' : 'Internal' }}
                                        </span>
                                    </td>

                                    {{-- 6. PRIORITY --}}
                                    <td class="py-3 px-4 border-r border-gray-200 dark:border-gray-700 text-center align-middle">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold text-white block w-24 mx-auto
                        {{ $ticket->priority == 'low' ? 'bg-green-500' : '' }}
                        {{ $ticket->priority == 'medium' ? 'bg-yellow-500' : '' }}
                        {{ $ticket->priority == 'high' ? 'bg-orange-500' : '' }}
                        {{ $ticket->priority == 'critical' ? 'bg-red-700' : '' }}">
                                            {{ strtoupper($ticket->priority) }}
                                        </span>
                                    </td>

                                    {{-- 7. STATUS (Hanya Badge dan Waktu Selesai) --}}
                                    <td class="py-3 px-4 border-r border-gray-200 dark:border-gray-700 align-middle">
                                        <div class="flex flex-col items-center justify-center w-full">
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold border w-24
                            {{ $ticket->status == 'open' ? 'bg-white text-red-700 border-red-300 dark:bg-gray-800 dark:text-red-400 dark:border-red-600' : '' }}
                            {{ $ticket->status == 'pending' ? 'bg-yellow-50 text-yellow-700 border-yellow-300 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-700' : '' }}
                            {{ $ticket->status == 'resolved' ? 'bg-green-100 text-green-800 border-green-400 dark:bg-green-900/30 dark:text-green-400 dark:border-green-700' : '' }}
                            {{ $ticket->status == 'closed' ? 'bg-gray-800 text-white border-gray-600 dark:bg-gray-600 dark:border-gray-500' : '' }}">
                                                {{ strtoupper($ticket->status) }}
                                            </span>

                                            @if(in_array($ticket->status, ['resolved', 'closed']))
                                            <div class="text-[10px] text-green-600 dark:text-green-400 font-medium mt-1.5 whitespace-nowrap text-center leading-tight">
                                                Selesai: {{ ($ticket->resolved_at ?? $ticket->updated_at)->format('d M y, H:i') }}
                                            </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- 8. AKSI --}}
                                    <td class="py-3 px-4 text-center align-middle">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-bold text-sm transition">
                                                Detail
                                            </a>

                                            @if(Auth::user()->role == 'admin')
                                            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('⚠️ Hapus Tiket Permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/30 transition" title="Hapus Tiket">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-gray-500 dark:text-gray-400 italic bg-gray-50 dark:bg-gray-800/50">
                                        Tidak ada data tiket yang ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>