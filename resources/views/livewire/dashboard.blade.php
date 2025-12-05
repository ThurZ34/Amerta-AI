<div>
    @section('header', 'Dashboard')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="min-h-screen bg-gray-200/50 dark:bg-gray-900 transition-colors duration-300 font-sans pb-24">

        {{-- Main Container --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

            {{-- 1. HEADER SECTION & AI INSIGHT --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
                {{-- Greeting --}}
                <div class="lg:col-span-2 flex flex-col justify-center">
                    <div class="flex items-center gap-2 mb-2">
                        <span
                            class="px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-semibold tracking-wide uppercase">
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        Halo, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="mt-2 text-lg text-gray-500 dark:text-gray-400 max-w-xl">
                        Aktivitas bisnismu hari ini terlihat <span
                            class="font-semibold text-indigo-600 dark:text-indigo-400">produktif</span>. Mari cek
                        detailnya.
                    </p>
                </div>

                {{-- AI Insight Card --}}
                @if ($aiMessage)
                    <div x-data="{ show: true }" x-show="show" x-transition
                        class="relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl shadow-indigo-100/50 dark:shadow-none border border-indigo-50 dark:border-indigo-500/20 overflow-hidden group">

                        {{-- Close Button --}}
                        <button wire:click="dismissInsight" @click="show = false"
                            class="absolute top-4 right-4 text-gray-300 dark:text-gray-600 hover:text-gray-500 dark:hover:text-gray-300 transition z-20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <div class="relative z-10 flex gap-4 items-start">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-500/20 flex items-center justify-center animate-pulse">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p
                                    class="text-xs font-bold text-indigo-600 dark:text-indigo-300 uppercase tracking-widest mb-1">
                                    Amerta Insight</p>
                                <p
                                    class="text-sm md:text-base font-medium leading-relaxed text-gray-700 dark:text-gray-200">
                                    "{{ $aiMessage }}"
                                </p>
                            </div>
                        </div>

                        {{-- Background Decor --}}
                        <div
                            class="absolute -bottom-10 -right-10 w-40 h-40 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none">
                        </div>
                    </div>
                @endif
            </div>

            {{-- 2. STATS GRID (4 Cards) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Saldo --}}
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition duration-300 group">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="p-2.5 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-500 dark:text-gray-400">Saldo Kas</span>
                        <div x-data="{ open: false }" class="relative">
                            <button @mouseenter="open = true" @mouseleave="open = false"
                                class="text-gray-400 hover:text-indigo-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute left-0 bottom-full mb-2 w-48 bg-gray-900 text-white text-xs rounded-lg py-2 px-3 shadow-lg z-10">
                                Uang fisik anda saat ini
                            </div>
                        </div>
                    </div>
                    <h3 class="text-2xl lg:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Rp
                        {{ number_format($cashBalance, 0, ',', '.') }}</h3>
                </div>

                {{-- Omset --}}
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition duration-300 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-2.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-500 dark:text-gray-400">Omset</span>
                            <div x-data="{ open: false }" class="relative">
                                <button @mouseenter="open = true" @mouseleave="open = false"
                                    class="text-gray-400 hover:text-emerald-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-transition
                                    class="absolute left-0 bottom-full mb-2 w-48 bg-gray-900 text-white text-xs rounded-lg py-2 px-3 shadow-lg z-10">
                                    Total Penjualan Kotor bulan ini (Belum dikurangi modal)
                                </div>
                            </div>
                        </div>
                        <span
                            class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $growthPercentage >= 0 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300' }}">
                            {{ $growthPercentage >= 0 ? '+' : '' }}{{ number_format($growthPercentage, 1) }}%
                        </span>
                    </div>
                    <h3 class="text-2xl lg:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Rp
                        {{ number_format($revenueThisMonth, 0, ',', '.') }}</h3>
                </div>

                {{-- Pengeluaran --}}
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition duration-300 group">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="p-2.5 bg-rose-50 dark:bg-rose-900/20 rounded-xl text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-500 dark:text-gray-400">Pengeluaran</span>
                        <div x-data="{ open: false }" class="relative">
                            <button @mouseenter="open = true" @mouseleave="open = false"
                                class="text-gray-400 hover:text-rose-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute left-0 bottom-full mb-2 w-48 bg-gray-900 text-white text-xs rounded-lg py-2 px-3 shadow-lg z-10">
                                Saldo Kas - Biaya Operasional (Kecuali bahan baku karena sudah masuk HPP)
                            </div>
                        </div>
                    </div>
                    <h3 class="text-2xl lg:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Rp
                        {{ number_format($expenseThisMonth, 0, ',', '.') }}</h3>
                </div>

                {{-- Profit --}}
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition duration-300 group">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="p-2.5 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-500 dark:text-gray-400">Profit Bersih</span>
                        <div x-data="{ open: false }" class="relative">
                            <button @mouseenter="open = true" @mouseleave="open = false"
                                class="text-gray-400 hover:text-indigo-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 bottom-full mb-2 w-56 bg-gray-900 text-white text-xs rounded-lg py-2 px-3 shadow-lg z-10">
                                Omset - (Modal Barang + Biaya Operasional). Ini keuntungan bersih Anda.
                            </div>
                        </div>
                    </div>
                    <h3
                        class="text-2xl lg:text-3xl font-black {{ $profitThisMonth >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-rose-600 dark:text-rose-400' }} tracking-tight">
                        Rp {{ number_format($profitThisMonth, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            {{-- 3. TREN PENJUALAN (Full Width) --}}
            <div
                class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">Tren Penjualan</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Analisa performa omset seiring waktu</p>
                    </div>
                    <div class="relative">
                        <select wire:model.live="range"
                            class="appearance-none bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 py-2.5 pl-4 pr-10 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <option value="day">Hari Ini</option>
                            <option value="week">7 Hari Terakhir</option>
                            <option value="month">Bulan Ini</option>
                            <option value="year">Tahun Ini</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="relative h-72 w-full" wire:key="sales-chart-wrapper-{{ $range }}">
                    <div wire:ignore class="h-full w-full"
                        x-data='{ labels: @json($chartLabels), data: @json($chartData) }'
                        x-init="const ctx = $el.querySelector('canvas').getContext('2d');
                        const isDark = document.documentElement.classList.contains('dark');
                        const gradient = ctx.createLinearGradient(0, 0, 0, 280);
                        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
                        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');
                        new Chart(ctx, {
                            type: 'line',
                            data: { labels: labels, datasets: [{ label: 'Penjualan (Rp)', data: data, borderColor: '#6366f1', backgroundColor: gradient, fill: true, tension: 0.4, pointBackgroundColor: '#6366f1', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6 }] },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { borderDash: [4, 4], color: isDark ? '#374151' : '#f3f4f6' }, ticks: { font: { size: 11 }, color: '#9ca3af' } }, x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#9ca3af' } } } }
                        });">
                        <canvas></canvas>
                    </div>
                </div>
            </div>

            {{-- 4. ALOKASI BIAYA + KESEHATAN BISNIS (2 Columns, Horizontal Cards) --}}
            @php
                $colorMap = [
                    'emerald' => [
                        'text' => 'text-emerald-500',
                        'ring' => 'text-emerald-500',
                        'soft_bg' => 'bg-emerald-50 dark:bg-emerald-500/10',
                    ],
                    'amber' => [
                        'text' => 'text-amber-500',
                        'ring' => 'text-amber-500',
                        'soft_bg' => 'bg-amber-50 dark:bg-amber-500/10',
                    ],
                    'rose' => [
                        'text' => 'text-rose-500',
                        'ring' => 'text-rose-500',
                        'soft_bg' => 'bg-rose-50 dark:bg-rose-500/10',
                    ],
                    'gray' => [
                        'text' => 'text-gray-400',
                        'ring' => 'text-gray-300',
                        'soft_bg' => 'bg-gray-100 dark:bg-gray-700',
                    ],
                ];
                $statusKey = $businessHealth['statusColor'] ?? 'gray';
                $colors = $colorMap[$statusKey];
                $score = $businessHealth['score'] ?? 0;
            @endphp
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Alokasi Biaya (Horizontal) --}}
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        {{-- Chart --}}
                        <div class="relative w-32 h-32 shrink-0"
                            wire:key="expense-chart-wrapper-{{ $range }}">
                            <div wire:ignore class="h-full w-full"
                                x-data='{ labels: @json($expenseLabels), data: @json($expenseData) }'
                                x-init="const ctx = $el.querySelector('canvas').getContext('2d');
                                const bgColors = ['#6366f1', '#10b981', '#f59e0b', '#f43f5e', '#a855f7'];
                                const isDummy = (data.length === 1 && labels[0] === 'Belum Ada Pengeluaran');
                                new Chart(ctx, {
                                    type: 'doughnut',
                                    data: { labels: isDummy ? ['Belum ada data'] : labels, datasets: [{ data: isDummy ? [1] : data, backgroundColor: isDummy ? ['#f3f4f6'] : bgColors, borderWidth: 0 }] },
                                    options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false } } }
                                });">
                                <canvas></canvas>
                            </div>
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 text-center sm:text-left">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Alokasi Biaya</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Distribusi pengeluaranmu bulan ini
                            </p>
                            <div class="inline-block px-4 py-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <span class="text-[10px] text-gray-400 uppercase font-bold">Total</span>
                                <p class="text-xl font-black text-gray-900 dark:text-white">Rp
                                    {{ number_format($expenseThisMonth, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kesehatan Bisnis (Horizontal) --}}
                <div x-data="{ showModal: false }" @click="showModal = true"
                    class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 cursor-pointer hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        {{-- Gauge --}}
                        <div class="relative w-24 h-24 shrink-0">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 96 96">
                                <circle cx="48" cy="48" r="40" fill="none" stroke="currentColor"
                                    stroke-width="8" class="text-gray-100 dark:text-gray-700" />
                                <circle cx="48" cy="48" r="40" fill="none" stroke="currentColor"
                                    stroke-width="8" stroke-dasharray="251"
                                    stroke-dashoffset="{{ 251 - ($score / 100) * 251 }}" stroke-linecap="round"
                                    class="{{ $colors['ring'] }} transition-all duration-1000" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span
                                    class="text-2xl font-black text-gray-900 dark:text-white">{{ $score }}</span>
                                <span class="text-[8px] uppercase font-bold text-gray-400">Skor</span>
                            </div>
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start gap-2 mb-1">
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Kesehatan Bisnis</h4>
                                <span
                                    class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $colors['soft_bg'] }} {{ $colors['text'] }}">{{ $businessHealth['status'] ?? 'N/A' }}</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Analisis performa bisnis oleh AI
                            </p>
                            <span class="text-xs text-indigo-500 font-semibold">Klik untuk lihat saran AI →</span>
                        </div>
                    </div>

                    {{-- Modal --}}
                    <div x-show="showModal" x-cloak class="fixed inset-0 z-50"
                        x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        {{-- Fixed Backdrop --}}
                        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showModal = false"></div>
                        {{-- Scrollable Content Wrapper --}}
                        <div class="fixed inset-0 overflow-y-auto">
                            <div class="min-h-full flex items-center justify-center p-4">
                                {{-- Modal Content - SCROLLABLE & RESPONSIVE --}}
                                <div class="relative bg-white dark:bg-gray-800 rounded-3xl p-5 sm:p-8 w-full max-w-2xl shadow-2xl my-8"
                                    x-transition:enter="ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100" @click.stop>

                                    {{-- Close Button --}}
                                    <button @click="showModal = false"
                                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>

                                    {{-- 2-Column Layout: Gauge Left, Message Right --}}
                                    <div class="flex flex-col sm:flex-row gap-6 items-center sm:items-start">
                                        {{-- LEFT: Gauge + Status --}}
                                        <div class="text-center shrink-0">
                                            <div class="relative w-28 h-28 mx-auto mb-3">
                                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 128 128">
                                                    <circle cx="64" cy="64" r="50" fill="none"
                                                        stroke="currentColor" stroke-width="10"
                                                        class="text-gray-100 dark:text-gray-700" />
                                                    <circle cx="64" cy="64" r="50" fill="none"
                                                        stroke="currentColor" stroke-width="10"
                                                        stroke-dasharray="314"
                                                        stroke-dashoffset="{{ 314 - ($score / 100) * 314 }}"
                                                        stroke-linecap="round"
                                                        class="{{ $colors['ring'] }} transition-all duration-1000" />
                                                </svg>
                                                <div
                                                    class="absolute inset-0 flex flex-col items-center justify-center">
                                                    <span
                                                        class="text-3xl font-black text-gray-900 dark:text-white">{{ $score }}</span>
                                                    <span
                                                        class="text-[9px] uppercase font-bold text-gray-400">Skor</span>
                                                </div>
                                            </div>
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-sm font-bold uppercase {{ $colors['soft_bg'] }} {{ $colors['text'] }}">
                                                {{ $businessHealth['status'] ?? 'N/A' }}
                                            </span>
                                        </div>

                                        {{-- RIGHT: Title + AI Message --}}
                                        <div class="flex-1 min-w-0">
                                            <h3
                                                class="text-xl font-bold text-gray-900 dark:text-white mb-4 text-center sm:text-left">
                                                Analisis Kesehatan Bisnis</h3>

                                            <div
                                                class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl p-4 border border-indigo-100 dark:border-indigo-800">
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-800 flex items-center justify-center shrink-0">
                                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-300"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p
                                                            class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest mb-1">
                                                            Saran Amerta AI</p>
                                                        <p
                                                            class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                                            {{ $businessHealth['message'] ?? 'Terus pantau perkembangan bisnis Anda!' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4 flex justify-end">
                                                <button @click="showModal = false"
                                                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">Mengerti</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. RECENT TRANSACTIONS --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div
                    class="p-8 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">Transaksi Terakhir</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Arus kas masuk dan keluar terbaru
                        </p>
                    </div>
                    <a href="{{ route('riwayat.index') }}"
                        class="group flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 bg-indigo-50 dark:bg-indigo-900/30 px-5 py-2.5 rounded-xl transition">
                        Lihat Semua
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-gray-50/50 dark:bg-gray-700/30 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700 uppercase tracking-wider text-xs">
                            <tr>
                                <th class="px-8 py-5 font-semibold">Keterangan</th>
                                <th class="px-8 py-5 font-semibold">Tanggal</th>
                                <th class="px-8 py-5 font-semibold text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-custom dark:divide-gray-700/50">
                            @forelse ($recentTransactions as $trx)
                                @php
                                    $isInflow = $trx->is_inflow;
                                    $categoryName = $trx->coa
                                        ? $trx->coa->name
                                        : ($isInflow
                                            ? 'Pemasukan'
                                            : 'Pengeluaran');
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-200">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm {{ $isInflow ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400' }}">
                                                @if ($isInflow)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <p
                                                    class="font-bold text-gray-900 dark:text-white text-base truncate max-w-[200px] sm:max-w-xs">
                                                    {{ Str::limit($trx->description, 50) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                    {{ $categoryName }} • {{ $trx->payment_method ?? 'Kas' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-gray-600 dark:text-gray-300 font-medium">
                                        {{ $trx->transaction_date->format('d M Y') }}
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <span
                                            class="text-base font-black {{ $isInflow ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                            {{ $isInflow ? '+' : '-' }} Rp
                                            {{ number_format($trx->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-16 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                            <div
                                                class="w-16 h-16 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="font-medium">Belum ada transaksi terbaru.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($recentTransactions->hasPages())
                    <div
                        class="px-8 py-5 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        {{ $recentTransactions->links() }}
                    </div>
                @endif
            </div>


        </div>
    </div>
</div>
