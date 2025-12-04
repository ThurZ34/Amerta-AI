@extends('layouts.app')

@section('header', 'Menu Utama')

@section('content') <div class="min-h-screen bg-gray-50 dark:bg-gray-950 transition-colors duration-300">

        <div class="relative bg-indigo-600 pb-32 overflow-hidden">
            <div class="absolute inset-0">
                <img class="w-full h-full object-cover opacity-20 dark:opacity-10 mix-blend-overlay"
                    src="[https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80](https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80)"
                    alt="Background">
                <div class="absolute inset-0 bg-gradient-to-b from-indigo-600/90 to-indigo-900/95 mix-blend-multiply"></div>
            </div>

            <div
                class="relative max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl md:text-5xl mb-2">
                        Halo, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="text-indigo-100 text-lg max-w-2xl mb-4 leading-relaxed">
                        {{ $insight }}
                    </p>
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md shadow-lg">
                        <span class="text-indigo-200 text-sm font-medium">Profit Kemarin:</span>
                        <span class="text-white font-bold tracking-wide">Rp
                            {{ number_format($profitYesterday, 0, ',', '.') }}</span>
                        @if ($profitYesterday > 0)
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        @elseif($profitYesterday < 0)
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col gap-3">
                    <div
                        class="flex items-center gap-4 bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl hover:bg-white/15 transition">
                        <div class="p-3 bg-green-500/20 rounded-xl border border-green-400/20">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-xs text-indigo-200 font-bold uppercase tracking-wider">Status Sistem</p>
                            <p class="text-white font-bold text-lg">Semua Layanan Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <main class="-mt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 relative z-10">

            @if ($lowStockProducts->count() > 0)
                <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-full"
                    class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-lg relative">
                    <button @click="show = false" class="absolute top-2 right-2 text-red-400 hover:text-red-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 pr-6">
                            <h3 class="text-sm leading-5 font-bold text-red-800">
                                Perhatian: Stok Menipis
                            </h3>
                            <div class="mt-2 text-sm leading-5 text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($lowStockProducts as $product)
                                        <li>
                                            <span class="font-bold">{{ $product->nama_produk }}</span>
                                            (Sisa: {{ $product->inventori }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div x-data="{ showModal: false }"
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            Target
                            Bulan Ini</h3>
                    </div>

                    <div class="flex items-end gap-2 mb-3">
                        <span
                            class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($targetPercentage, 0) }}%</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 mb-1">tercapai</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-1">
                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $targetPercentage }}%"></div>
                    </div>
                    <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-2 font-medium">
                        {{ $targetPercentage >= 100 ? 'Luar biasa! Target tercapai.' : 'Semangat! Sedikit lagi.' }}
                    </p>
                    <div class="flex items-center justify-between mt-1 relative z-10">
                        <p class="text-xs text-gray-400">Target: Rp {{ number_format($targetRevenue, 0, ',', '.') }}</p>
                        <button @click="showModal = true"
                            class="text-indigo-600 hover:text-indigo-800 text-xs font-bold">Ubah</button>
                    </div>

                    {{-- Modal Update Target --}}
                    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div
                            class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="showModal" @click="showModal = false" x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity"
                                aria-hidden="true">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>

                            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <form action="{{ route('main_menu.update-target') }}" method="POST">
                                    @csrf
                                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                                    id="modal-title">
                                                    Ubah Target Bulanan
                                                </h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                                        Tentukan target omset yang ingin Anda capai bulan ini.
                                                    </p>
                                                    <input type="number" name="target_revenue"
                                                        value="{{ $targetRevenue }}"
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                                        placeholder="Masukkan nominal target (Rp)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Simpan
                                        </button>
                                        <button type="button" @click="showModal = false"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Produk
                        Jagoan</h3>

                    @forelse ($topProducts as $product)
                        <div class="mb-3 last:mb-0">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate"
                                title="{{ $product->nama_produk }}">
                                {{ $product->nama_produk }}
                            </h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span
                                    class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[10px] font-bold">
                                    Stok: {{ $product->inventori }}
                                </span>
                                <span class="text-[10px] text-gray-500">Favorit Pelanggan</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic mt-2">Belum ada data produk.</p>
                    @endforelse
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Disiplin Laporan 🔥</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-rose-600 dark:text-rose-400">
                            {{ $streakDays }}
                        </span>
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Hari Beruntun</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Hebat! Pertahankan konsistensimu.</p>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-8 w-1 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Operasional Harian</h3>
                    </div>

                    <a href="{{ route('daily-checkin.index') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 dark:bg-emerald-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                        </div>

                        <div class="relative flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-emerald-100 dark:bg-emerald-500/20 rounded-xl flex items-center justify-center shrink-0 group-hover:rotate-6 transition-transform">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-lg font-bold text-gray-900 dark:text-white mb-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                    Laporan Harian</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pantau dan kelola transaksi harian
                                    bisnis Anda.</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('riwayat.index') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-rose-50 dark:bg-rose-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                        </div>
                        <div class="relative flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-rose-100 dark:bg-rose-500/20 rounded-xl flex items-center justify-center shrink-0 group-hover:rotate-6 transition-transform">
                                <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-lg font-bold text-gray-900 dark:text-white mb-1 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                                    Catat Pengeluaran</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Rekap pengeluaran operasional dengan
                                    mudah.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-8 w-1 bg-purple-500 rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Manajemen Bisnis</h3>
                    </div>

                    <a href="{{ route('produk.index') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-purple-50 dark:bg-purple-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                        </div>
                        <div class="relative flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-purple-100 dark:bg-purple-500/20 rounded-xl flex items-center justify-center shrink-0 group-hover:rotate-6 transition-transform">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-lg font-bold text-gray-900 dark:text-white mb-1 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                    Manajemen Produk</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Atur katalog produk, harga, dan stok
                                    barang.</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('profil_bisnis') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-orange-50 dark:bg-orange-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                        </div>
                        <div class="relative flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-orange-100 dark:bg-orange-500/20 rounded-xl flex items-center justify-center shrink-0 group-hover:rotate-6 transition-transform">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-lg font-bold text-gray-900 dark:text-white mb-1 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                    Profil Bisnis</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola informasi bisnis dan pengaturan
                                    dasar.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-8 w-1 bg-blue-500 rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Analisis & Bantuan</h3>
                    </div>

                    <a href="{{ route('dashboard') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-blue-50 dark:bg-blue-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                        </div>
                        <div class="relative flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-blue-100 dark:bg-blue-500/20 rounded-xl flex items-center justify-center shrink-0 group-hover:rotate-6 transition-transform">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-lg font-bold text-gray-900 dark:text-white mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    Dashboard Utama</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan performa bisnis dan grafik
                                    analisis.</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('amerta') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-indigo-600/0 to-purple-600/0 dark:from-indigo-900/40 dark:to-purple-900/40 transition-opacity">
                        </div>

                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 dark:bg-indigo-500/10 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-150 duration-500">
                        </div>

                        <div class="relative flex items-start gap-4 z-10">
                            <div
                                class="w-12 h-12 bg-indigo-100 dark:bg-indigo-500/30 backdrop-blur-sm rounded-xl flex items-center justify-center shrink-0 border border-indigo-50 dark:border-indigo-500/20 group-hover:rotate-12 transition-transform">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Amerta AI Assistant</h4>
                                <p class="text-sm text-gray-500 dark:text-indigo-200 ">Konsultasi cerdas untuk strategi
                                    bisnis Anda.</p>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </main>
    </div>
@endsection
