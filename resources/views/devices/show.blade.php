<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Detail Perangkat:') }} {{ $device->hostname }}
                </h2>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 rounded-full text-xs font-bold uppercase whitespace-nowrap">
                    Uptime: {{ $device->uptime_days ?? 0 }} Hari
                </span>
            </div>
            <a href="{{ route('devices.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 font-bold text-sm transition-colors whitespace-nowrap">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-indigo-500">
                    <h3 class="text-sm font-bold text-gray-500 uppercase mb-4">Spesifikasi Hardware</h3>
                    <div class="space-y-3 dark:text-gray-300">
                        <p><strong>Model:</strong> {{ $device->pc_model ?? '-' }}</p>
                        <p><strong>CPU:</strong> {{ $device->cpu_name ?? '-' }}</p>
                        <p><strong>RAM:</strong> {{ $device->total_ram_gb ?? '-' }} GB</p>
                        <p><strong>Serial:</strong> <span class="font-mono text-xs">{{ $device->serial_number }}</span></p>
                        <p><strong>OS:</strong> {{ $device->os_version }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-green-500">
                    <h3 class="text-sm font-bold text-gray-500 uppercase mb-4">Metrik Kinerja</h3>
                    <div class="space-y-4 dark:text-gray-300">
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span>Beban CPU</span>
                                <span>{{ $device->cpu_usage_percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $device->cpu_usage_percent }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span>Pemakaian RAM</span>
                                <span>{{ $device->ram_usage_percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                                <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ $device->ram_usage_percent }}%"></div>
                            </div>
                        </div>
                        <p class="text-xs mt-4 pt-2 border-t dark:border-gray-700">
                            <strong>Terakhir Boot:</strong> {{ $device->last_boot_time ? $device->last_boot_time->format('d M Y, H:i') : '-' }}
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-yellow-500">
                    <h3 class="text-sm font-bold text-gray-500 uppercase mb-4">Jaringan & Power</h3>
                    <div class="space-y-3 dark:text-gray-300">
                        <p><strong>IP:</strong> {{ $device->ip_address }}</p>
                        <p><strong>SSID:</strong> {{ $device->wifi_ssid ?? 'N/A' }}</p>
                        <p><strong>MAC:</strong> <span class="font-mono text-xs">{{ $device->mac_address }}</span></p>
                        <div class="pt-2">
                            <p class="text-xs mb-1">Kesehatan Baterai:</p>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-4 dark:bg-gray-700 overflow-hidden">
                                    <div class="h-4 {{ $device->battery_health_percent < 50 ? 'bg-red-500' : 'bg-green-500' }}" style="width: {{ $device->battery_health_percent }}%"></div>
                                </div>
                                <span class="text-xs font-bold">{{ $device->battery_health_percent }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4 dark:text-white">Status Penyimpanan (Storage)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-sm mb-2 dark:text-gray-300">Drive C: (Free Space)</p>
                        <div class="flex items-center gap-4">
                            <div class="flex-1 bg-gray-200 rounded-full h-6 dark:bg-gray-700 overflow-hidden">
                                <div class="h-6 {{ $device->free_disk_c_percent < 10 ? 'bg-red-600' : 'bg-indigo-600' }}" style="width: {{ $device->free_disk_c_percent }}%"></div>
                            </div>
                            <span class="font-bold dark:text-white">{{ $device->free_disk_c_percent }}%</span>
                        </div>
                    </div>
                    @if($device->free_disk_d_percent !== null)
                    <div>
                        <p class="text-sm mb-2 dark:text-gray-300">Drive D: (Free Space)</p>
                        <div class="flex items-center gap-4">
                            <div class="flex-1 bg-gray-200 rounded-full h-6 dark:bg-gray-700 overflow-hidden">
                                <div class="h-6 {{ $device->free_disk_d_percent < 10 ? 'bg-red-600' : 'bg-blue-500' }}" style="width: {{ $device->free_disk_d_percent }}%"></div>
                            </div>
                            <span class="font-bold dark:text-white">{{ $device->free_disk_d_percent }}%</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- HISTORY TIKET UNTUK PERANGKAT INI -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4 dark:text-white">Histori Kerusakan (Tiket Terkait)</h3>
                
                @if($device->tickets && $device->tickets->count() > 0)
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead class="bg-gray-100 dark:bg-gray-700/50">
                            <tr>
                                <th class="py-2 px-3 border-b dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">ID</th>
                                <th class="py-2 px-3 border-b dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Tanggal</th>
                                <th class="py-2 px-3 border-b dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Masalah</th>
                                <th class="py-2 px-3 border-b dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300">Status</th>
                                <th class="py-2 px-3 border-b dark:border-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($device->tickets as $ticket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="py-2 px-3 text-sm text-gray-500 dark:text-gray-400">#{{ $ticket->id }}</td>
                                <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $ticket->created_at->format('d M Y') }}</td>
                                <td class="py-2 px-3 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $ticket->title }}</td>
                                <td class="py-2 px-3 text-sm">
                                    <span class="px-2 py-0.5 rounded text-xs font-bold border 
                                        {{ $ticket->status == 'open' ? 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                        {{ $ticket->status == 'in_progress' ? 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                        {{ $ticket->status == 'resolved' ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                        {{ $ticket->status == 'closed' ? 'bg-gray-800 text-white border-gray-600' : '' }}
                                        {{ in_array($ticket->status, ['pending', 'cancelled']) ? 'bg-gray-100 text-gray-600 border-gray-300 dark:bg-gray-700' : '' }}
                                    ">
                                        {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-bold hover:underline">Lihat Tiket</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg text-center border border-dashed border-gray-300 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada histori kerusakan atau tiket yang terhubung ke perangkat ini.</p>
                </div>
                @endif
            </div>

            @if($device->battery_report_path)
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg transition-colors duration-300">
                <div class="p-6 border-b dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                        Laporan Kesehatan Baterai (Full HTML)
                    </h3>
                    <a href="{{ route('devices.report', $device->id) }}?download=1" class="bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white text-xs font-bold py-1.5 px-3 rounded shadow-sm transition-colors">
                        Download HTML
                    </a>
                </div>

                <div class="p-0 overflow-hidden rounded-b-lg">
                    <iframe
                        src="{{ route('devices.report', $device->id) }}"
                        class="w-full h-[600px] border-none bg-white">
                    </iframe>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>