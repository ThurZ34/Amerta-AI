<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50" x-data="themeManager"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Menu Utama - {{ config('app.name', 'Amerta AI') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Alpine is handled by app.js usually, but we include logic here --}}

    @livewireStyles

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="h-full antialiased text-gray-900 bg-gray-50 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

        {{-- Hero Section --}}
        <div class="relative bg-gray-900 pb-32">
            <div class="absolute inset-0">
                <img class="w-full h-full object-cover" src="{{ asset('images/main_menu_bg.jpg') }}"
                    alt="Background Banner">
                <div class="absolute inset-0 bg-black/40">
                </div>
            </div>

            <div
                class="relative max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
                {{-- Left Side: Greeting & Stats --}}
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl md:text-5xl mb-2">
                        Halo, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="text-indigo-100 text-lg max-w-2xl mb-4 leading-relaxed">
                        {{ $insight }}
                    </p>

                    {{-- Profit Badge --}}
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md shadow-lg">
                        <span class="text-indigo-200 text-sm font-medium">Profit Kemarin:</span>
                        <span class="text-white font-bold tracking-wide">
                            Rp {{ number_format($profitYesterday, 0, ',', '.') }}
                        </span>
                        @if ($profitYesterday > 0)
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
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

                {{-- Right Side: Profile & Tools --}}
                <div class="flex items-center gap-4">
                    {{-- Profile Section Wrapper --}}
                    <div class="flex flex-col gap-2">
                        {{-- User Profile Card --}}
                        <div
                            class="flex items-center gap-3 bg-white/10 border border-white/20 backdrop-blur-md px-4 py-2 rounded-2xl transition-all duration-300">
                            {{-- Avatar (Klik untuk ke Edit Profile) --}}
                            <a href="{{ route('profile.edit') }}"
                                class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg overflow-hidden border border-white/30 hover:ring-2 hover:ring-indigo-400 transition-all">
                                @if (Auth::user()->business && Auth::user()->business->logo)
                                    <img src="{{ asset('storage/' . Auth::user()->business->logo) }}" alt="Logo"
                                        class="w-full h-full object-cover">
                                @else
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                @endif
                            </a>

                            {{-- Text & Logout Button --}}
                            <div class="text-left hidden sm:block">
                                {{-- Nama User --}}
                                <a href="{{ route('profile.edit') }}"
                                    class="text-sm font-bold text-white hover:text-indigo-200 transition-colors block leading-tight">
                                    {{ Auth::user()->name }}
                                </a>

                                {{-- Role & Logout --}}
                                <div class="flex items-center gap-2 mt-0.5">
                                    <p class="text-[10px] text-indigo-200 uppercase tracking-wider">
                                        {{ Auth::user()->role ?? 'Owner' }}
                                    </p>
                                    <span class="text-indigo-200/40 text-[10px]">|</span>

                                    {{-- Tombol Logout --}}
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" onclick="confirmLogout(event)"
                                            class="text-[10px] font-bold text-rose-300 hover:text-rose-100 transition-colors cursor-pointer flex items-center gap-1">
                                            Keluar
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Business Profile Card (NEW) --}}
                        <a href="{{ route('manajemen.profil-bisnis.index') }}"
                            class="flex items-center gap-3 bg-white/10 border border-white/20 backdrop-blur-md px-4 py-2 rounded-2xl hover:bg-white/20 transition-all duration-300 group">
                            {{-- Icon Toko --}}
                            <div
                                class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white border border-white/30 group-hover:ring-2 group-hover:ring-orange-400 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>

                            <div class="text-left hidden sm:block">
                                <span
                                    class="text-sm font-bold text-white group-hover:text-orange-200 transition-colors block leading-tight">
                                    Profil Bisnis
                                </span>
                                <p class="text-[10px] text-indigo-200">
                                    Kelola Data Toko
                                </p>
                            </div>
                        </a>
                    </div>

                    {{-- Theme Toggle --}}
                    <button @click="toggleTheme()"
                        class="p-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md text-white transition-all duration-300 group"
                        aria-label="Toggle Dark Mode">
                        {{-- Sun Icon (Show in Dark Mode) --}}
                        <svg x-show="darkMode" x-cloak
                            class="w-6 h-6 text-yellow-300 group-hover:rotate-12 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        {{-- Moon Icon (Show in Light Mode) --}}
                        <svg x-show="!darkMode"
                            class="w-6 h-6 text-indigo-100 group-hover:-rotate-12 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Main Content Container --}}
        <main class="-mt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 relative z-10">

            {{-- Top Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Card 1: Target Bulanan --}}
                <div x-data="{ showModal: false }"
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-indigo-600 dark:text-indigo-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>

                    <div class="relative z-10">
                        <h3
                            class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            Target Bulan Ini</h3>
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
                        <p class="text-xs text-gray-400">Target: Rp {{ number_format($targetRevenue, 0, ',', '.') }}
                        </p>
                        <button @click="showModal = true"
                            class="text-indigo-600 hover:text-indigo-800 dark:hover:text-indigo-300 text-xs font-bold transition-colors">
                            Ubah
                        </button>
                    </div>

                    {{-- Modal Update Target --}}
                    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                        aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div
                            class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="showModal" @click="showModal = false"
                                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true">
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div x-show="showModal"
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
                                        class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                                        <button type="submit"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:w-auto sm:text-sm">Simpan</button>
                                        <button type="button" @click="showModal = false"
                                            class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:w-auto sm:text-sm">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Produk Jagoan --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-amber-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Produk Jagoan</h3>
                    @forelse ($topProducts as $product)
                        <div class="mb-3 last:mb-0 relative z-10">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate"
                                title="{{ $product->nama_produk }}">{{ $product->nama_produk }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span
                                    class="text-[10px] bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full dark:bg-amber-900 dark:text-amber-200">Favorit
                                    Pelanggan</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic mt-2">Belum ada data produk.</p>
                    @endforelse
                </div>

                {{-- Card 3: Streak Laporan --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-rose-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Disiplin Laporan 🔥</h3>
                    <div class="flex items-baseline gap-2 relative z-10">
                        <span class="text-3xl font-black text-rose-600 dark:text-rose-400">{{ $streakDays }}</span>
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Hari Beruntun</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 relative z-10">Hebat! Pertahankan konsistensimu.</p>
                </div>
            </div>

            {{-- Main Navigation Menu Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Column 1: Operasional --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-8 w-1 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Operasional Harian</h3>
                    </div>

                    {{-- Menu Item: Laporan Harian --}}
                    <a href="{{ route('operasional.analisis-penjualan.index') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        {{-- Background Gradient & Blob --}}
                        <div
                            class="absolute inset-0 bg-linear-to-br from-emerald-600/0 to-teal-600/0 dark:from-emerald-900/20 dark:to-teal-900/20 transition-opacity">
                        </div>
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 dark:bg-emerald-500/10 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-150 duration-500">
                        </div>

                        {{-- Content --}}
                        <div class="relative flex items-start gap-4 z-10">
                            <div
                                class="w-12 h-12 bg-emerald-100 dark:bg-emerald-500/20 backdrop-blur-sm rounded-xl flex items-center justify-center shrink-0 border border-emerald-50 dark:border-emerald-500/20 group-hover:rotate-12 transition-transform">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Laporan Harian</h4>
                                <p class="text-sm text-gray-500 dark:text-emerald-100/70">Pantau dan kelola transaksi
                                    harian bisnis Anda.</p>
                            </div>
                        </div>
                    </a>

                    {{-- Menu Item: Catat Pengeluaran --}}
                    <a href="{{ route('operasional.riwayat-keuangan.index') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="absolute inset-0 bg-linear-to-br from-rose-600/0 to-red-600/0 dark:from-rose-900/20 dark:to-red-900/20 transition-opacity">
                        </div>
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-rose-50 dark:bg-rose-500/10 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-150 duration-500">
                        </div>

                        <div class="relative flex items-start gap-4 z-10">
                            <div
                                class="w-12 h-12 bg-rose-100 dark:bg-rose-500/20 backdrop-blur-sm rounded-xl flex items-center justify-center shrink-0 border border-rose-50 dark:border-rose-500/20 group-hover:rotate-12 transition-transform">
                                <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Catat Pengeluaran</h4>
                                <p class="text-sm text-gray-500 dark:text-rose-100/70">Rekap pengeluaran operasional
                                    dengan mudah.</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Column 2: Manajemen --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-8 w-1 bg-purple-500 rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Manajemen Bisnis</h3>
                    </div>

                    {{-- Menu Item: Produk --}}
                    <a href="{{ route('manajemen.produk.index') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="absolute inset-0 bg-linear-to-br from-purple-600/0 to-fuchsia-600/0 dark:from-purple-900/20 dark:to-fuchsia-900/20 transition-opacity">
                        </div>
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-purple-50 dark:bg-purple-500/10 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-150 duration-500">
                        </div>

                        <div class="relative flex items-start gap-4 z-10">
                            <div
                                class="w-12 h-12 bg-purple-100 dark:bg-purple-500/20 backdrop-blur-sm rounded-xl flex items-center justify-center shrink-0 border border-purple-50 dark:border-purple-500/20 group-hover:rotate-12 transition-transform">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Manajemen Produk</h4>
                                <p class="text-sm text-gray-500 dark:text-purple-100/70">Atur katalog produk anda
                                    dengan mudah.</p>
                            </div>
                        </div>
                    </a>

                    {{-- Menu Item: Profil Bisnis --}}
                    {{-- Menu Item: Kasir (Replaces Profil Bisnis) --}}
                    <a href="{{ route('operasional.kasir') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="absolute inset-0 bg-linear-to-br from-orange-600/0 to-amber-600/0 dark:from-orange-900/20 dark:to-amber-900/20 transition-opacity">
                        </div>
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-orange-50 dark:bg-orange-500/10 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-150 duration-500">
                        </div>

                        <div class="relative flex items-start gap-4 z-10">
                            <div
                                class="w-12 h-12 bg-orange-100 dark:bg-orange-500/20 backdrop-blur-sm rounded-xl flex items-center justify-center shrink-0 border border-orange-50 dark:border-orange-500/20 group-hover:rotate-12 transition-transform">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Kasir Toko</h4>
                                <p class="text-sm text-gray-500 dark:text-orange-100/70">Proses transaksi penjualan
                                    dengan cepat.</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Column 3: Analisis & AI --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-8 w-1 bg-blue-500 rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Analisis & Bantuan</h3>
                    </div>

                    {{-- Menu Item: Dashboard --}}
                    <a href="{{ route('analisis.dashboard') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="absolute inset-0 bg-linear-to-br from-blue-600/0 to-cyan-600/0 dark:from-blue-900/20 dark:to-cyan-900/20 transition-opacity">
                        </div>
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-blue-50 dark:bg-blue-500/10 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-150 duration-500">
                        </div>

                        <div class="relative flex items-start gap-4 z-10">
                            <div
                                class="w-12 h-12 bg-blue-100 dark:bg-blue-500/20 backdrop-blur-sm rounded-xl flex items-center justify-center shrink-0 border border-blue-50 dark:border-blue-500/20 group-hover:rotate-12 transition-transform">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Dashboard Utama</h4>
                                <p class="text-sm text-gray-500 dark:text-blue-100/70">Ringkasan performa bisnis dan
                                    grafik analisis.</p>
                            </div>
                        </div>
                    </a>

                    {{-- Menu Item: Amerta AI (Reference Card) --}}
                    <a href="{{ route('amerta') }}"
                        class="block group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="absolute inset-0 bg-linear-to-br from-indigo-600/0 to-purple-600/0 dark:from-indigo-900/40 dark:to-purple-900/40 transition-opacity">
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
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Amerta AI Assistant
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-indigo-200">Konsultasi cerdas untuk strategi
                                    bisnis Anda.</p>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </main>
    </div>

    {{-- Script for Alpine Theme Logic handled at top, init is automatic with defer/module or included script --}}
    <script>
        document.addEventListener('alpine:init', () => {
            // Theme Logic
            Alpine.data('themeManager', () => ({
                darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches),
                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Use Laravel Session ID to track the welcome message state uniquely for each login session
            const sessionId = "{{ session()->getId() }}";
            const storageKey = 'welcome_shown_' + sessionId;

            // Check if welcome message has already been shown for THIS session
            if (!localStorage.getItem(storageKey)) {
                const userName = "{{ Auth::user()->name }}";
                const isDarkMode = document.documentElement.classList.contains('dark');
                const bgColor = isDarkMode ? '#1f2937' : '#ffffff';
                const textColor = isDarkMode ? '#f9fafb' : '#111827';

                Swal.fire({
                    title: 'Selamat Datang!',
                    text: `Halo ${userName}, selamat datang!`,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    background: bgColor,
                    color: textColor,
                    position: 'center',
                    toast: false
                });

                // Mark as shown for this session
                localStorage.setItem(storageKey, 'true');

                // Optional cleanup: Remove old keys from other sessions to keep localStorage clean (simple check)
                // This is a basic cleanup strategy
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (key && key.startsWith('welcome_shown_') && key !== storageKey) {
                        localStorage.removeItem(key);
                    }
                }
            }
        });

        function confirmLogout(event) {
            event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari sesi ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    @livewireScripts
</body>

</html>
