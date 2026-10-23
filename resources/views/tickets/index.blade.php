<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Manajemen Tiket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-lg font-bold">Daftar Tiket</h3>
                        <a href="{{ route('tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors">
                            + Buat Tiket Baru
                        </a>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg mb-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                        <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">

                            <div class="col-span-2">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Judul / Nama User..."
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition-colors">
                            </div>

                            <select name="status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                <option value="">- Semua Status -</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>

                            <select name="priority" class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition-colors">
                                <option value="">- Semua Prioritas -</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>

                            </select>

                            <button type="submit" class="bg-gray-800 dark:bg-gray-700 hover:bg-gray-900 dark:hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm transition-colors">
                                Filter
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                        <table class="min-w-full bg-white dark:bg-gray-800 transition-colors duration-300">
                            <thead class="bg-gray-100 dark:bg-gray-700/50">
                                <tr>
                                    <th class="py-3 px-4 border-b dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-600 uppercase tracking-wider">Tiket ID</th>
                                    <th class="py-3 px-4 border-b dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-600  uppercase tracking-wider">Pelapor</th>
                                    <th class="py-3 px-4 border-b dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-600  uppercase tracking-wider">Masalah</th>
                                    <th class="py-3 px-4 border-b dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-600  uppercase tracking-wider">Kategori</th>
                                    <th class="py-3 px-4 border-b dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-600  uppercase tracking-wider">Lingkup</th>
                                    <th class="py-3 px-4 border-b dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-600  uppercase tracking-wider">Prioritas</th>
                                    <th class="py-3 px-4 border-b dark:border-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-600    uppercase tracking-wider">Dibuat</th>
                                    <th class="py-3 px-4 border-b dark:border-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-600    uppercase tracking-wider">Status</th>
                                    <th class="py-3 px-4 border-b dark:border-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-600    uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($tickets as $ticket)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400">#{{ $ticket->id }}</td>

                                    <td class="py-3 px-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $ticket->user->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->reporter_name ? 'Pelapor: '.$ticket->reporter_name : '' }}</div>
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $ticket->title }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($ticket->description, 40) }}</div>
                                    </td>

                                    <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $ticket->category->name ?? '-' }}
                                    </td>

                                    <td class="py-3 px-4 align-middle">
                                        @if($ticket->scope == 'eksternal')
                                            <span class="text-orange-600 dark:text-orange-400 font-bold text-xs uppercase bg-orange-50 dark:bg-orange-900/30 px-2 py-1 rounded">Eksternal</span>
                                        @else
                                            <span class="text-teal-600 dark:text-teal-400 font-bold text-xs uppercase bg-teal-50 dark:bg-teal-900/30 px-2 py-1 rounded">Internal</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 align-middle">

                                        @if($ticket->priority == 'high') <span class="text-orange-600 dark:text-orange-400 font-bold text-xs uppercase">High</span>
                                        @elseif($ticket->priority == 'medium') <span class="text-yellow-600 dark:text-yellow-400 font-bold text-xs uppercase">Medium</span>
                                        @else <span class="text-green-600 dark:text-green-400 font-bold text-xs uppercase">Low</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 text-center align-middle whitespace-nowrap border-l border-gray-100 dark:border-gray-700">
                                        <div class="text-[13px] font-bold text-gray-700 dark:text-gray-300">
                                            {{ $ticket->created_at->format('d M Y') }}
                                        </div>
                                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $ticket->created_at->format('H:i') }} WIB
                                        </div>
                                    </td>

                                    <td class="py-3 px-4 text-center align-middle border-l border-r border-gray-100 dark:border-gray-700">
                                        <div class="flex flex-col items-center justify-center w-full">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold border w-24 text-center
                        {{ $ticket->status == 'open' ? 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50' : '' }}
                        {{ $ticket->status == 'in_progress' ? 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50' : '' }}
                        {{ $ticket->status == 'pending' ? 'bg-yellow-50 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-800/50' : '' }}
                        {{ $ticket->status == 'resolved' ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50' : '' }}
                        {{ $ticket->status == 'closed' ? 'bg-gray-800 text-white border-gray-600 dark:bg-gray-600 dark:border-gray-500' : '' }}
                        {{ $ticket->status == 'cancelled' ? 'bg-gray-200 text-gray-500 border-gray-300 line-through dark:bg-gray-800 dark:text-gray-500 dark:border-gray-700' : '' }}">
                                                {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}
                                            </span>

                                            @if(in_array($ticket->status, ['resolved', 'closed']))
                                            <div class="text-[10px] text-green-600 dark:text-green-400 font-medium mt-1.5 whitespace-nowrap text-center leading-tight">
                                                Selesai: {{ ($ticket->resolved_at ?? $ticket->updated_at)->format('d M Y, H:i') }}
                                            </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="py-3 px-4 text-sm font-medium text-center align-middle">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-bold transition">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="py-8 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50">
                                        Data tidak ditemukan.
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