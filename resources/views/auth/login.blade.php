@extends('auth.layout')

@section('title', 'Login')

@section('image_url',
    'https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80')

@section('content')
    <div class="text-left">
        <h1 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">Selamat Datang Kembali</h1>
        <p class="text-gray-500 dark:text-gray-400">Silakan masukkan detail akun anda.</p>
    </div>

    <a href="{{ route('google.login') }}"
        class="w-full flex items-center justify-center gap-3 border border-gray-300 rounded-lg p-3 hover:bg-gray-50 transition">
        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
        <span class="text-sm font-medium text-gray-700">Masuk dengan Google</span>
    </a>

    <div class="relative flex py-2 items-center">
        <div class="grow border-t border-gray-200 dark:border-gray-700"></div>
        <span class="shrink-0 mx-4 text-gray-400 text-sm">atau dengan email</span>
        <div class="grow border-t border-gray-200 dark:border-gray-700"></div>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input autocomplete="off" type="email" name="email" required autofocus
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-indigo-500 focus:border-transparent outline-none transition bg-white dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                placeholder="nama@email.com">
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <div class="flex justify-between items-center mb-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors">Lupa
                        Password?</a>
                @endif
            </div>
            <input type="password" name="password" required
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-indigo-500 focus:border-transparent outline-none transition bg-white dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                placeholder="••••••••">
        </div>

        <button type="submit"
            class="w-full bg-black dark:bg-indigo-600 text-white font-semibold py-3 rounded-lg hover:bg-gray-800 dark:hover:bg-indigo-700 transition duration-300 shadow-lg dark:shadow-indigo-900/20">
            Masuk
        </button>
    </form>

    <div class="text-center text-sm text-gray-600 dark:text-gray-400">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-black dark:text-indigo-400 hover:underline">Daftar
            Sekarang</a>
    </div>
@endsection
