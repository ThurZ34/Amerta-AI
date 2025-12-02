@extends('layouts.app')

@section('header', 'Analisa Penjualan Anda')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Input Penjualan</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Tanggal:
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</p>
                </div>
                <a href="{{ route('daily-checkin.index') }}"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    &larr; Kembali ke Kalender
                </a>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <form action="{{ route('daily-checkin.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">

                    <div class="p-6 sm:p-8 space-y-6">

                        <div class="grid grid-cols-1 gap-6">
                            @forelse ($produks as $produk)
                                <div
                                    class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 transition hover:border-indigo-300 dark:hover:border-indigo-500">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-600 overflow-hidden flex-shrink-0">
                                            @if ($produk->gambar)
                                                <img src="{{ asset('storage/' . $produk->gambar) }}"
                                                    alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $produk->nama_produk }}
                                            </h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Harga: Rp
                                                {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <label for="sales_{{ $produk->id }}"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:block">Terjual:</label>
                                        <input type="number" name="sales[{{ $produk->id }}]"
                                            id="sales_{{ $produk->id }}" value="0" min="0"
                                            class="w-24 px-3 py-2 text-center rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white font-bold text-lg transition shadow-sm">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Unit</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada produk. Silakan tambah produk
                                        terlebih dahulu.</p>
                                    <a href="{{ route('produk.index') }}"
                                        class="text-indigo-600 hover:underline mt-2 inline-block">Ke Katalog Produk</a>
                                </div>
                            @endforelse
                        </div>

                    </div>

                    @if ($produks->count() > 0)
                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:scale-105 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Simpan & Analisa
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
