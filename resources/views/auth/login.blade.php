<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 transition-colors duration-300">IT Service Desk</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors duration-300">Silakan masuk untuk membuat atau memantau tiket.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email Kantor')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@perusahaan.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="current-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4 flex justify-between items-center">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors duration-300" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400 transition-colors duration-300">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
            <a class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors duration-300" href="{{ route('password.request') }}">
                {{ __('Lupa Password?') }}
            </a>
            @endif
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full justify-center !bg-blue-600 hover:!bg-blue-700 focus:!bg-blue-700 active:!bg-blue-900 ring-blue-500">
                {{ __('Masuk Portal') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>