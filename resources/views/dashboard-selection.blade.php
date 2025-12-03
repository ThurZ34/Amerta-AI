@extends('layouts.app')

@section('header', 'Pilih Jalur Anda')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                Selamat Datang di Amerta
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Pilih bagaimana Anda ingin memulai
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-4xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-4">

                <!-- Card: Buat Bisnis Baru -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-2xl hover:shadow-xl transition-shadow duration-300 border border-gray-100 dark:border-gray-700 relative group">
                    <div
                        class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-500 rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500">
                    </div>

                    <div class="px-6 py-8 sm:p-10 flex flex-col h-full">
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-2xl mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Buat Bisnis Baru</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-8 grow">
                            Saya adalah pemilik bisnis dan ingin mengatur semuanya dari awal.
                        </p>

                        <a href="{{ route('setup-bisnis') }}"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Mulai Setup Bisnis &rarr;
                        </a>
                    </div>
                </div>

                <!-- Card: Gabung Tim -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-2xl hover:shadow-xl transition-shadow duration-300 border border-gray-100 dark:border-gray-700 relative group">
                    <div
                        class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-emerald-500 rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500">
                    </div>

                    <div class="px-6 py-8 sm:p-10 flex flex-col h-full">
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-2xl mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Gabung Tim</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6 flex-grow">
                            Saya karyawan atau anggota tim yang ingin bergabung ke bisnis yang sudah ada.
                        </p>

                        <form action="{{ route('dashboard-selection.join') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="invite_code" class="sr-only">Kode Undangan</label>
                                <input type="text" name="invite_code" id="invite_code" required
                                    class="appearance-none block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm dark:bg-gray-900 dark:text-white uppercase tracking-widest text-center font-mono"
                                    placeholder="Masukkan Kode Invite">
                            </div>

                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                                Gabung Tim
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
