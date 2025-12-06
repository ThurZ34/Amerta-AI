@extends('auth.layout2')

@section('title', 'Daftar Akun')

@section('image_url', asset('images/banner_login.png'))
@section('content')

    <div class="text-left mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create an Account</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm">
            Mulai perjalanan anda bersama kami.
        </p>
    </div>

    <div class="relative flex py-2 items-center">
        <div class="grow border-t border-gray-200 dark:border-gray-700"></div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- NAMA LENGKAP --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Nama Lengkap
            </label>
            <input
                autocomplete="off"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-indigo-500 focus:border-transparent outline-none transition bg-white dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                placeholder="Masukkan Nama Anda">
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- EMAIL --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Email
            </label>
            <div
                class="flex items-center gap-3 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-3 bg-white dark:bg-gray-800">
                <img src="{{ asset('images/email.png') }}" class="w-5 h-5 opacity-80" alt="email">
                <input
                    autocomplete="off"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="flex-1 outline-none bg-transparent dark:text-white placeholder-gray-400"
                    placeholder="Masukkan Email Anda">
            </div>
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- PASSWORD --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Password
            </label>
            <div
                class="flex items-center gap-3 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-3 bg-white dark:bg-gray-800">
                <img src="{{ asset('images/password.png') }}" class="w-5 h-5 opacity-80" alt="password">
                <input
                    type="password"
                    name="password"
                    required
                    class="flex-1 outline-none bg-transparent dark:text-white placeholder-gray-400"
                    placeholder="Minimal 8 karakter">
            </div>
            @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- ULANGI PASSWORD --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Ulangi Password
            </label>
            <div
                class="flex items-center gap-3 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-3 bg-white dark:bg-gray-800">
                <img src="{{ asset('images/password.png') }}" class="w-5 h-5 opacity-80" alt="password">
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="flex-1 outline-none bg-transparent dark:text-white placeholder-gray-400"
                    placeholder="Ketik ulang password">
            </div>
        </div>

        {{-- TOMBOL DAFTAR --}}
        <button type="submit"
            class="w-full bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 text-lg rounded-xl transition shadow-md">
            Daftar
        </button>
    </form>

    <div class="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-purple-500 hover:underline">
            Masuk disini
        </a>
    </div>

@endsection
