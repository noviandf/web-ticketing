<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Monitoring Perangkat') }}
            </h2>

            <a href="{{ route('devices.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded shadow flex items-center text-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <div class="mb-6 flex flex-col md:flex-row gap-4 items-end">
    <form action="{{ route('devices.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full">
        <div class="flex-1">
            <x-input-label for="search" :value="__('Cari Nama Hostname')" />
            <x-text-input id="search" name="search" type="text" class="block mt-1 w-full" 
                placeholder="Ketik nama pc..." :value="request('search')" />
        </div>

        <div class="w-full md:w-48">
            <x-input-label for="sort" :value="__('Urutkan Berdasarkan')" />
            <select id="sort" name="sort" onchange="this.form.submit()" 
                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Nama A-Z</option>
                <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Nama Z-A</option>
            </select>
        </div>

        <div>
            <x-primary-button type="submit" class="mb-0.5">
                {{ __('Cari') }}
            </x-primary-button>
            @if(request()->has('search') || request()->has('sort'))
                <a href="{{ route('devices.index') }}" class="ml-2 text-sm text-red-600 dark:text-red-400 hover:underline">Reset</a>
            @endif
        </div>
    </form>
</div>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hostname / Model</th>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">User</th>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">CPU / RAM</th>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Storage (C/D)</th>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Baterai</th>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Last Sync</th>
                                    <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($devices as $device)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="font-bold">{{ $device->hostname }}</div>
                                        <div class="text-xs text-gray-500">{{ $device->pc_model ?? 'Unknown Model' }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $device->logged_in_user }}</td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm">CPU: <span class="{{ $device->cpu_usage_percent > 80 ? 'text-orange-500 font-bold' : '' }}">{{ $device->cpu_usage_percent }}%</span></div>
                                        <div class="text-sm">RAM: {{ $device->ram_usage_percent }}%</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm">C: <span class="{{ $device->free_disk_c_percent < 10 ? 'text-red-600 font-bold' : '' }}">{{ $device->free_disk_c_percent }}%</span></div>
                                        @if($device->free_disk_d_percent !== null)
                                        <div class="text-sm">D: <span class="{{ $device->free_disk_d_percent < 10 ? 'text-red-600 font-bold' : '' }}">{{ $device->free_disk_d_percent }}%</span></div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 rounded text-xs {{ $device->battery_health_percent < 50 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $device->battery_health_percent }}%
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                                        {{ $device->last_sync ? $device->last_sync->diffForHumans() : '-' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('devices.show', $device) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 font-medium">Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $devices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>