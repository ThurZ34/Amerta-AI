@extends('auth.layout')

@section('title', 'Login')

@section('image_url',
    'https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80')

@section('content')

    <div class="text-left mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Login to Continue</h1>
    </div>

    {{-- Google Login --}}
    <a href="{{ route('google.login') }}"
       class="w-full flex items-center justify-center gap-3 border border-gray-300 rounded-lg p-3 hover:bg-gray-50 transition">
        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="" class="w-5 h-5">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Masuk dengan Google</span>
    </a>

    {{-- Garis pemisah --}}
    <div class="relative flex py-3 items-center">
        <div class="grow border-t border-gray-200 dark:border-gray-700"></div>
        <span class="shrink-0 mx-4 text-gray-400 text-sm">atau</span>
        <div class="grow border-t border-gray-200 dark:border-gray-700"></div>
    </div>

    {{-- FORM LOGIN --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- EMAIL --}}
        <div>
            <div class="flex items-center gap-3 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-3 bg-white dark:bg-gray-800">
                <img src="https://www.svgrepo.com/show/494325/mail.svg" class="w-6 h-6 opacity-70">
                <input autocomplete="off" type="email" name="email" required autofocus
                    class="flex-1 outline-none bg-transparent dark:text-white placeholder-gray-400"
                    placeholder="Email">
            </div>
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- PASSWORD --}}
        <div>
            <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300"></span>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white">
                        Forgot Password ?
                    </a>
                @endif
            </div>

            <div class="flex items-center gap-3 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-3 bg-white dark:bg-gray-800">
                <img src="https://www.svgrepo.com/show/491399/lock.svg" class="w-6 h-6 opacity-70">
                <input type="password" name="password" required
                    class="flex-1 outline-none bg-transparent dark:text-white placeholder-gray-400"
                    placeholder="Password">
            </div>
        </div>

        {{-- TOMBOL LOGIN BESAR --}}
        <button type="submit"
            class="w-full bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 text-lg rounded-xl transition shadow-md">
            LOGIN
        </button>

    </form>

    {{-- Teks bawah --}}
    <p class="text-center text-gray-600 dark:text-gray-400 text-sm mt-4">
        By continuing, you agree to the
        <span class="font-medium text-black dark:text-white">Terms of use</span>
        and
        <span class="font-medium text-black dark:text-white">Privacy Policy.</span>
    </p>

    <div class="text-center text-sm text-gray-600 dark:text-gray-400 mt-2">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-purple-500 hover:underline">Daftar Sekarang</a>
    </div>

@endsection
