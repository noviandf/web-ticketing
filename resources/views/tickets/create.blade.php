<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Tiket Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                <div class="mb-4 bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded relative">
                    <strong class="font-bold">Ada yang salah!</strong>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="reporter_name" :value="__('Nama Pelapor (Wajib)')" />
                        <x-text-input id="reporter_name" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="reporter_name" :value="old('reporter_name')" required autofocus placeholder="Masukkan Nama Anda" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="cubicle_number" :value="__('No. Cubicle / Meja (Khusus Ruang Agent)')" />
                        <x-text-input id="cubicle_number" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" type="text" name="cubicle_number" :value="old('cubicle_number')" placeholder="Isi nomor meja (Kosongkan jika di luar Ruang Agent)" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="title" :value="__('Judul Masalah')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus placeholder="Contoh: Printer macet, Wifi lantai 2 mati" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="item_type" :value="__('Type Kendala / Item')" />

                        <select id="item_type" name="item_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                            <option value="" disabled selected>-- Pilih Item yang Bermasalah --</option>

                            <optgroup label="Perangkat Keras (Hardware)" class="dark:bg-gray-800">
                                <option value="Printer" data-category="Hardware" class="dark:bg-gray-900">Printer</option>
                                <option value="Laptop" data-category="Hardware" class="dark:bg-gray-900">Laptop / PC</option>
                                <option value="Monitor" data-category="Hardware" class="dark:bg-gray-900">Monitor</option>
                                <option value="Mouse" data-category="Hardware" class="dark:bg-gray-900">Mouse</option>
                                <option value="Keyboard" data-category="Hardware" class="dark:bg-gray-900">Keyboard</option>
                                <option value="Headset Agent" data-category="Hardware" class="dark:bg-gray-900">Headset Agent</option>
                                <option value="Kelistrikan" data-category="Hardware" class="dark:bg-gray-900">Kelistrikan / Power</option>
                                <option value="Baterai" data-category="Hardware" class="dark:bg-gray-900">Baterai / UPS</option>
                            </optgroup>

                            <optgroup label="Aplikasi & Software" class="dark:bg-gray-800">
                                <option value="OS/Windows" data-category="Software" class="dark:bg-gray-900">OS / Windows</option>
                                <option value="CMS" data-category="Software" class="dark:bg-gray-900">CMS</option>
                                <option value="Microsip" data-category="Software" class="dark:bg-gray-900">Microsip</option>
                                <option value="Mcsys" data-category="Software" class="dark:bg-gray-900">Mcsys</option>
                                <option value="TV Wallboard" data-category="Software" class="dark:bg-gray-900">Tampilan Wallboard</option>
                                <option value="Browser" data-category="Software" class="dark:bg-gray-900">Web</option>
                                <option value="App" data-category="Software" class="dark:bg-gray-900">Apps</option>
                                <option value="Office Tools" data-category="Software" class="dark:bg-gray-900">Office Tools</option>
                                <option value="Email Penjaminan" data-category="Software" class="dark:bg-gray-900">Email Penjaminan</option>
                                <option value="System" data-category="Software" class="dark:bg-gray-900">System Error Lainnya</option>
                            </optgroup>

                            <optgroup label="Jaringan (Network & Voice)" class="dark:bg-gray-800">
                                <option value="Jaringan Internet" data-category="Internet" class="dark:bg-gray-900">
                                    Jaringan Internet
                                </option>

                                <option value="Telephony / VoIP" data-category="Internet" class="dark:bg-gray-900">
                                    Jaringan Telepon
                                </option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="category_id" :value="__('Kategori (Terisi Otomatis)')" />

                        <select id="category_id" class="block mt-1 w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 pointer-events-none" tabindex="-1">
                            <option value="">-- Menunggu Pilihan Item --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="category_id" id="hidden_category_id">
                    </div>

                    <div class="mb-4">
                        <x-input-label for="location_select" :value="__('Lokasi Kejadian')" />

                        <div class="flex gap-2">
                            <select id="location_select" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <option value="" disabled selected class="dark:bg-gray-900">-- Pilih Ruangan --</option>
                                <option value="Ruang Agent (Call Center)" class="dark:bg-gray-900">Ruang Agent</option>
                                <option value="Ruang Manajemen / Direksi" class="dark:bg-gray-900">Ruang Manajemen</option>
                                <option value="Ruang Meeting Utama" class="dark:bg-gray-900">Ruang Meeting</option>
                                <option value="Ruang Server / IT" class="dark:bg-gray-900">Ruang Server</option>
                                <option value="Lobby / Resepsionis" class="dark:bg-gray-900">Lobby / Resepsionis</option>
                                <option value="Pantry / Area Istirahat" class="dark:bg-gray-900">Pantry</option>
                                <option value="other" class="dark:bg-gray-900">Lainnya (Tulis Sendiri...)</option>
                            </select>
                        </div>

                        <input type="text" id="location_manual" name="location"
                            class="hidden mt-2 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                            placeholder="Tulis detail lokasi di sini..." disabled>

                        <input type="hidden" id="final_location" name="location">
                    </div>

                    <div class="mb-4">
                        <x-input-label for="priority" :value="__('Prioritas (Otomatis berdasarkan Item)')" />
                        <select id="priority_display" class="border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-md shadow-sm block mt-1 w-full pointer-events-none" disabled>
                            <option value="">-- Menunggu Pilihan Item --</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                        <input type="hidden" name="priority" id="hidden_priority" value="low">
                    </div>

                    <div class="mb-4">
                        <x-input-label for="photo" :value="__('Foto Bukti (Opsional)')" />
                        <input id="photo" class="block mt-1 w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 focus:outline-none" type="file" name="photo" accept="image/*">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Format: JPG, PNG, JPEG. Max: 2MB.</p>
                    </div>

                    <div class="mb-6">
                        <x-input-label for="description" :value="__('Deskripsi Lengkap')" />
                        <textarea id="description" name="description" rows="4" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required placeholder="Jelaskan detail masalahnya di sini...">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4 transition-colors">Batal</a>
                        <x-primary-button>
                            {{ __('Kirim Tiket') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. LOGIKA OTOMATIS KATEGORI DAN PRIORITAS ---
            const itemSelect = document.getElementById('item_type');
            const categorySelect = document.getElementById('category_id');
            const hiddenCategoryInput = document.getElementById('hidden_category_id');
            
            const priorityDisplay = document.getElementById('priority_display');
            const hiddenPriorityInput = document.getElementById('hidden_priority');

            itemSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const categoryKeyword = selectedOption.getAttribute('data-category');
                const itemValue = this.value;

                if (categoryKeyword) {
                    for (let i = 0; i < categorySelect.options.length; i++) {
                        const optionText = categorySelect.options[i].text.toLowerCase();
                        if (optionText.includes(categoryKeyword.toLowerCase())) {
                            categorySelect.selectedIndex = i;
                            hiddenCategoryInput.value = categorySelect.options[i].value;
                            break;
                        }
                    }
                }
                
                // Logika Prioritas
                let priority = 'low'; // Default
                const highPriorityItems = ['System', 'Jaringan Internet', 'Telephony / VoIP'];
                const mediumPriorityItems = ['Kelistrikan', 'TV Wallboard', 'Mcsys', 'CMS', 'Email Penjaminan'];
                
                if (highPriorityItems.includes(itemValue)) {
                    priority = 'high';
                } else if (mediumPriorityItems.includes(itemValue)) {
                    priority = 'medium';
                }
                
                priorityDisplay.value = priority;
                hiddenPriorityInput.value = priority;
            });

            // --- 2. LOGIKA LOKASI ---
            const selectLoc = document.getElementById('location_select');
            const manualLoc = document.getElementById('location_manual');
            const finalLoc = document.getElementById('final_location');

            selectLoc.addEventListener('change', function() {
                if (this.value === 'other') {
                    manualLoc.classList.remove('hidden');
                    manualLoc.disabled = false;
                    manualLoc.focus();
                    manualLoc.required = true;
                    finalLoc.value = "";
                } else {
                    manualLoc.classList.add('hidden');
                    manualLoc.disabled = true;
                    manualLoc.required = false;
                    finalLoc.value = this.value; 
                }
            });

            manualLoc.addEventListener('input', function() {
                finalLoc.value = this.value;
            });
        });
    </script>
</x-app-layout>