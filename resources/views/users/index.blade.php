<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Kelola User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded relative transition-colors duration-300">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300 border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Daftar Karyawan</h3>
                        <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors">
                            + Tambah User Baru
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-left border-collapse bg-white dark:bg-gray-800 transition-colors duration-300">
                            <thead class="bg-gray-100 dark:bg-gray-700/50 text-gray-600 dark:text-gray-600">
                                <tr>
                                    <th class="p-3 border-b dark:border-gray-700">Nama</th>
                                    <th class="p-3 border-b dark:border-gray-700">Email</th>
                                    <th class="p-3 border-b dark:border-gray-700">Role</th>
                                    <th class="p-3 border-b dark:border-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="p-3">{{ $user->name }}</td>
                                    <td class="p-3">{{ $user->email }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 text-xs font-bold rounded 
                                            {{ $user->role == 'admin' ? 'bg-red-200 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 
                                               ($user->role == 'agent' ? 'bg-blue-200 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                            {{ strtoupper($user->role) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-sm flex gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 dark:bg-yellow-500 dark:hover:bg-yellow-600 text-xs transition-colors">
                                            Edit
                                        </a>

                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}? Data tidak bisa dikembalikan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-xs transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>