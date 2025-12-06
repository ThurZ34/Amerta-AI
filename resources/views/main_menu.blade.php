@extends('layouts.app')

@section('header', 'Menu Utama')

@section('content')
<div class="min-h-screen w-full bg-gray-50 dark:bg-gray-950 transition-colors duration-300 font-sans">

    {{-- HERO SECTION --}}
    <div class="relative bg-indigo-700 pb-32 overflow-hidden">
        {{-- Background Image & Overlay --}}
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover opacity-20 dark:opacity-10 mix-blend-overlay"
                src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80"
                alt="Background Pattern">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/95 via-indigo-800/95 to-purple-900/90 mix-blend-multiply"></div>
        </div>

        {{-- Content Hero --}}
        <div class="relative max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-left space-y-4">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl md:text-5xl">
                        Halo, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="mt-2 text-indigo-100 text-lg max-w-2xl leading-relaxed font-light">
                        {{ $insight }}
                    </p>
                </div>
                
                {{-- Profit Badge --}}
                <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-white/10 border border-white/20 backdrop-blur-md shadow-xl transition-transform hover:scale-105 cursor-default">
                    <span class="text-indigo-200 text-sm font-medium">Profit Kemarin:</span>
                    <span class="text-white font-bold tracking-wide text-lg">Rp {{ number_format($profitYesterday, 0, ',', '.') }}</span>
                    @if ($profitYesterday > 0)
                        <div class="bg-green-500/20 p-1 rounded-full">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    @elseif($profitYesterday < 0)
                        <div class="bg-red-500/20 p-1 rounded-full">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <main class="-mt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 relative z-10">

        {{-- TOP STATS CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Card 1: Target Bulanan (With Modal) --}}
            <div x-data="{ showModal: false }" class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl border border-gray-100 dark:border-gray-700/50 relative overflow-hidden group hover:shadow-2xl transition-all duration-300">
                <div class="absolute -right-6 -top-6 p-4 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:rotate-12 duration-500">
                    <svg class="w-32 h-32 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Target Bulan Ini</h3>
                            <button @click="showModal = true" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 text-xs font-bold bg-indigo-50 dark:bg-indigo-900/30 px-2 py-1 rounded transition-colors">
                                UBAH
                            </button>
                        </div>

                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                                {{ number_format($targetPercentage, 0) }}<span class="text-xl">%</span>
                            </span>
                        </div>

                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 mb-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-full rounded-full transition-all duration-1000 ease-out relative" style="width: {{ $targetPercentage }}%">
                                <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-2">
                        <p class="text-xs font-medium {{ $targetPercentage >= 100 ? 'text-green-600 dark:text-green-400' : 'text-indigo-600 dark:text-indigo-400' }}">
                            {{ $targetPercentage >= 100 ? '🎉 Luar biasa! Target tercapai.' : '🚀 Semangat! Sedikit lagi.' }}
                        </p>
                        <p class="text-[11px] text-gray-400 mt-1 font-mono">Target: Rp {{ number_format($targetRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Modal Backdrop & Panel --}}
                <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showModal" 
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false" aria-hidden="true"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div x-show="showModal" 
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                             class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            
                            <form action="{{ route('main_menu.update-target') }}" method="POST">
                                @csrf
                                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Ubah Target Omset</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Masukkan nominal target baru yang ingin dicapai bulan ini.</p>
                                                <div class="relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                                    </div>
                                                    <input type="number" name="target_revenue" value="{{ $targetRevenue }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-600 rounded-md py-2 dark:bg-gray-700 dark:text-white shadow-sm" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm transition-colors">
                                        Simpan Perubahan
                                    </button>
                                    <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Produk Jagoan --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl border border-gray-100 dark:border-gray-700/50 relative overflow-hidden group hover:shadow-2xl transition-all duration-300">
                <div class="absolute -right-6 -top-6 p-4 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:rotate-12 duration-500">
                    <svg class="w-32 h-32 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>

                <div class="relative z-10">
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-4">Produk Terlaris</h3>
                    
                    <div class="space-y-4">
                        @forelse ($topProducts as $index => $product)
                            <div class="flex items-center gap-3 group/item">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xs">
                                    #{{ $index + 1 }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate" title="{{ $product->nama_produk }}">
                                        {{ $product->nama_produk }}
                                    </h4>
                                    <div class="h-1 w-12 bg-amber-500 rounded-full mt-1 group-hover/item:w-full transition-all duration-500"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-400 italic">Belum ada data penjualan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Card 3: Streak Laporan --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl border border-gray-100 dark:border-gray-700/50 relative overflow-hidden group hover:shadow-2xl transition-all duration-300">
                <div class="absolute -right-6 -top-6 p-4 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:rotate-12 duration-500">
                    <svg class="w-32 h-32 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                    </svg>
                </div>

                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-4">Konsistensi Laporan</h3>
                        
                        <div class="flex items-end gap-2">
                            <span class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-rose-500 to-orange-500">
                                {{ $streakDays }}
                            </span>
                            <span class="text-lg text-gray-600 dark:text-gray-300 font-bold mb-2">Hari 🔥</span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-rose-50 dark:bg-rose-900/20 rounded-lg border border-rose-100 dark:border-rose-800/30">
                        <p class="text-xs text-rose-700 dark:text-rose-300 text-center font-medium">
                            "Konsistensi adalah kunci kesuksesan bisnis."
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- MENU GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- COLUMN 1: OPERASIONAL --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3 mb-4 pl-1">
                    <div class="h-6 w-1.5 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight">Operasional Harian</h3>
                </div>

                <a href="{{ route('daily-checkin.index') }}" class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-1 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/0 via-emerald-500/0 to-emerald-500/0 group-hover:from-emerald-500/5 group-hover:to-emerald-500/20 transition-all duration-500 rounded-2xl"></div>
                    <div class="relative p-5 flex items-start gap-5 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700/50 group-hover:border-emerald-200 dark:group-hover:border-emerald-700/50 transition-colors">
                        <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-500/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                            <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <div class="pt-1">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Laporan Harian</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-snug">Pantau transaksi dan arus kas harian.</p>
                        </div>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('riwayat.index') }}" class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-1 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-r from-rose-500/0 via-rose-500/0 to-rose-500/0 group-hover:from-rose-500/5 group-hover:to-rose-500/20 transition-all duration-500 rounded-2xl"></div>
                    <div class="relative p-5 flex items-start gap-5 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700/50 group-hover:border-rose-200 dark:group-hover:border-rose-700/50 transition-colors">
                        <div class="w-14 h-14 bg-rose-50 dark:bg-rose-500/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                            <svg class="w-7 h-7 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="pt-1">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Catat Pengeluaran</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-snug">Rekap biaya operasional dengan mudah.</p>
                        </div>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>
            </div>

            {{-- COLUMN 2: MANAJEMEN BISNIS --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3 mb-4 pl-1">
                    <div class="h-6 w-1.5 bg-purple-500 rounded-full shadow-[0_0_10px_rgba(168,85,247,0.5)]"></div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight">Manajemen Bisnis</h3>
                </div>

                <a href="{{ route('produk.index') }}" class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-1 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/0 via-purple-500/0 to-purple-500/0 group-hover:from-purple-500/5 group-hover:to-purple-500/20 transition-all duration-500 rounded-2xl"></div>
                    <div class="relative p-5 flex items-start gap-5 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700/50 group-hover:border-purple-200 dark:group-hover:border-purple-700/50 transition-colors">
                        <div class="w-14 h-14 bg-purple-50 dark:bg-purple-500/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                            <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div class="pt-1">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Produk & Stok</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-snug">Kelola katalog produk, harga, dan stok.</p>
                        </div>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('profil_bisnis') }}" class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-1 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500/0 via-orange-500/0 to-orange-500/0 group-hover:from-orange-500/5 group-hover:to-orange-500/20 transition-all duration-500 rounded-2xl"></div>
                    <div class="relative p-5 flex items-start gap-5 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700/50 group-hover:border-orange-200 dark:group-hover:border-orange-700/50 transition-colors">
                        <div class="w-14 h-14 bg-orange-50 dark:bg-orange-500/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                            <svg class="w-7 h-7 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div class="pt-1">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Profil Bisnis</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-snug">Pengaturan informasi dasar usaha Anda.</p>
                        </div>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>
            </div>

            {{-- COLUMN 3: ANALISIS & AI --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3 mb-4 pl-1">
                    <div class="h-6 w-1.5 bg-blue-500 rounded-full shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight">Analisis & Bantuan</h3>
                </div>

                <a href="{{ route('dashboard') }}" class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-1 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/0 via-blue-500/0 to-blue-500/0 group-hover:from-blue-500/5 group-hover:to-blue-500/20 transition-all duration-500 rounded-2xl"></div>
                    <div class="relative p-5 flex items-start gap-5 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700/50 group-hover:border-blue-200 dark:group-hover:border-blue-700/50 transition-colors">
                        <div class="w-14 h-14 bg-blue-50 dark:bg-blue-500/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div class="pt-1">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Dashboard Utama</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-snug">Ringkasan grafik performa bisnis.</p>
                        </div>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('amerta') }}" class="block group relative rounded-2xl p-[2px] hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-500">
                    {{-- Gradient Border --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500 rounded-2xl opacity-70 group-hover:opacity-100 animate-gradient-xy transition-opacity"></div>
                    
                    <div class="relative h-full bg-white dark:bg-gray-900 rounded-[14px] p-5 overflow-hidden">
                        {{-- Background Decoration --}}
                        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl -mr-16 -mt-16 pointer-events-none"></div>
                        
                        <div class="relative flex items-center gap-5 z-10">
                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shrink-0 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">Amerta AI Assistant</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-300 mt-1 font-medium">Konsultasi cerdas untuk strategi bisnis.</p>
                            </div>
                        </div>
                    </div>
                </a>

            </div>

        </div>

    </main>
</div>
@endsection