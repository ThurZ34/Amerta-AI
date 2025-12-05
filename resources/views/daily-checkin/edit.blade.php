@extends('layouts.app')

@section('header', 'Edit Penjualan Harian')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-24" x-data="{ isLoading: false }">

        <div x-show="isLoading"
            class="fixed inset-0 z-100 flex items-center justify-center bg-gray-900/80 backdrop-blur-sm transition-opacity"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;" x-cloak>
            <div class="text-center">
                <div
                    class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-indigo-500 border-t-transparent mb-4">
                </div>
                <h3 class="text-xl font-bold text-white tracking-wide">Mengupdate Data...</h3>
                <p class="text-gray-300 mt-2 text-sm font-light">Amerta sedang memproses perubahan Anda.</p>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <a href="{{ route('daily-checkin.show', $dailySale->id) }}"
                        class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Detail
                    </a>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Penjualan</h2>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm rounded-xl px-4 py-2 flex items-center gap-3">
                    <div class="bg-indigo-100 dark:bg-indigo-900/50 p-2 rounded-lg text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Laporan
                            Tanggal</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ $dailySale->date->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('daily-checkin.update', $dailySale->id) }}" method="POST" @submit="isLoading = true">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-20">
                    @forelse ($produks as $produk)
                        <div x-data="{ count: {{ $existingSales[$produk->id] ?? 0 }} }"
                            class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-500 transition-all duration-300 p-3 flex gap-4 items-center">

                            <div
                                class="w-20 h-20 shrink-0 bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden relative">
                                @if ($produk->gambar)
                                    <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0 flex flex-col justify-between h-20 py-0.5">
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white leading-tight truncate"
                                        title="{{ $produk->nama_produk }}">
                                        {{ $produk->nama_produk }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between mt-auto">
                                    <label
                                        class="text-[10px] font-medium text-gray-400 uppercase tracking-wide">Terjual</label>

                                    <div
                                        class="flex items-center bg-gray-50 dark:bg-gray-700/50 rounded-lg p-0.5 border border-gray-200 dark:border-gray-600">
                                        <button type="button" @click="if(count > 0) count--"
                                            class="w-7 h-7 flex items-center justify-center rounded-md text-gray-500 hover:bg-white dark:hover:bg-gray-600 hover:text-indigo-600 dark:hover:text-indigo-400 hover:shadow-sm transition-all disabled:opacity-50">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                            </svg>
                                        </button>

                                        <input type="number" name="sales[{{ $produk->id }}]" x-model="count"
                                            min="0"
                                            class="w-10 text-center bg-transparent border-none p-0 text-sm font-bold text-gray-800 dark:text-white focus:ring-0 appearance-none [-moz-appearance:textfield] [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none">

                                        <button type="button" @click="count++"
                                            class="w-7 h-7 flex items-center justify-center rounded-md text-gray-500 hover:bg-white dark:hover:bg-gray-600 hover:text-indigo-600 dark:hover:text-indigo-400 hover:shadow-sm transition-all">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
                            <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-full mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada produk</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 max-w-sm">Tambahkan produk di menu
                                Katalog Produk terlebih dahulu sebelum mengisi laporan.</p>
                            <a href="{{ route('produk.index') }}"
                                class="mt-4 inline-flex items-center text-sm font-medium text-indigo-600 hover:underline">
                                Ke Katalog Produk &rarr;
                            </a>
                        </div>
                    @endforelse
                </div>

                @if ($produks->count() > 0)
                    <div
                        class="fixed bottom-0 left-0 right-0 p-4 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-t border-gray-200 dark:border-gray-800 z-40">
                        <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
                            <div class="hidden sm:block">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pastikan data yang diinput sudah
                                    sesuai.</p>
                            </div>
                            <div class="flex gap-3 w-full sm:w-auto">
                                <a href="{{ route('daily-checkin.show', $dailySale->id) }}"
                                    class="flex-1 sm:flex-none justify-center inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-3 px-6 rounded-xl transition-all">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="flex-1 sm:flex-none justify-center inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/20 transition-all active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Update & Analisa</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </form>

        </div>
    </div>
@endsection
