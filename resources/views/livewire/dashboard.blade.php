<div>
    @section('header', 'Dashboard')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="font-sans pb-24">

        <div class="w-full px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            {{-- 1. HEADER SECTION & AI INSIGHT --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 flex flex-col justify-center">
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mt-1">
                        Halo, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
                        Siap memantau perkembangan bisnis hari ini?
                    </p>
                </div>

                <div x-data="{ show: true }" x-show="show" x-transition
                    class="relative bg-white dark:bg-gradient-to-br dark:from-indigo-600 dark:to-violet-700 rounded-3xl p-6 shadow-2xl shadow-indigo-500/10 dark:shadow-indigo-500/30 overflow-hidden transform hover:scale-[1.02] transition duration-300 border border-indigo-100 dark:border-none">

                    <button @click="show = false"
                        class="absolute top-4 right-4 text-gray-400 dark:text-white/60 hover:text-gray-600 dark:hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <div class="relative z-10 flex gap-4 items-start">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-white/20 backdrop-blur-md flex items-center justify-center animate-pulse border border-indigo-100 dark:border-white/10">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-white" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p
                                class="text-xs font-bold text-indigo-600 dark:text-indigo-200 uppercase tracking-widest mb-1">
                                Amerta Insight
                            </p>
                            <p class="text-base font-medium leading-relaxed text-gray-800 dark:text-white">
                                "{{ $aiMessage }}"
                            </p>
                        </div>
                    </div>

                    <div
                        class="absolute -bottom-6 -right-6 w-32 h-32 bg-indigo-50 dark:bg-white/10 rounded-full blur-2xl">
                    </div>
                    <div
                        class="absolute top-0 left-0 w-full h-full bg-linear-to-b from-white/50 dark:from-white/5 to-transparent pointer-events-none">
                    </div>
                </div>
            </div>

        </div>

        {{-- LOW STOCK ALERT --}}
        @if ($lowStockProducts->count() > 0)
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 w-full">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Perhatian: Stok Menipis</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($lowStockProducts as $product)
                                    <li>
                                        <span class="font-bold">{{ $product->nama_produk }}</span> tersisa
                                        {{ $product->inventori }} unit (Min: {{ $product->min_stock }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('produk.index') }}"
                                class="text-sm font-medium text-red-800 dark:text-red-200 hover:text-red-900 dark:hover:text-white underline">
                                Kelola Stok &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 2. STATS GRID (High Shadow & Lift Effect) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white dark:bg-gray-900 p-6 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800 hover:-translate-y-1 transition duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-2xl text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Saldo
                        Kas</span>
                </div>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white">Rp
                    {{ number_format($cashBalance, 0, ',', '.') }}</h3>
            </div>

            <div
                class="bg-white dark:bg-gray-900 p-6 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800 hover:-translate-y-1 transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl text-emerald-600 dark:text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <span
                            class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Omset</span>
                    </div>
                    <span
                        class="text-xs font-bold px-3 py-1 rounded-full {{ $growthPercentage >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ $growthPercentage >= 0 ? '+' : '' }}{{ number_format($growthPercentage, 1) }}%
                    </span>
                </div>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white">Rp
                    {{ number_format($revenueThisMonth, 0, ',', '.') }}</h3>
            </div>

            <div
                class="bg-white dark:bg-gray-900 p-6 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800 hover:-translate-y-1 transition duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-3 bg-rose-50 dark:bg-rose-900/20 rounded-2xl text-rose-600 dark:text-rose-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                    <span
                        class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pengeluaran</span>
                </div>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white">Rp
                    {{ number_format($expenseThisMonth, 0, ',', '.') }}</h3>
            </div>

            <div
                class="bg-white dark:bg-gray-900 p-6 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800 hover:-translate-y-1 transition duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl text-indigo-600 dark:text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Profit
                        Bersih</span>
                </div>
                <h3
                    class="text-3xl font-black {{ $profitThisMonth >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-rose-600' }}">
                    Rp {{ number_format($profitThisMonth, 0, ',', '.') }}
                </h3>
            </div>
        </div>

        {{-- 3. CHARTS SECTION (Shadow Lebih Tebal) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-900 p-8 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">Tren Penjualan</h4>
                        <p class="text-sm text-gray-500">Performa omset seiring waktu</p>
                    </div>

                    <div class="relative">
                        <select wire:model.live="range"
                            class="appearance-none bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 py-2.5 pl-4 pr-10 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer shadow-sm hover:bg-gray-100 transition">
                            <option value="day">Hari Ini</option>
                            <option value="week">7 Hari Terakhir</option>
                            <option value="month">Bulan Ini</option>
                            <option value="year">Tahun Ini</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="relative h-80 w-full" wire:key="sales-chart-wrapper-{{ $range }}">
                    <div wire:ignore class="h-full w-full"
                        x-data='{ labels: @json($chartLabels), data: @json($chartData) }'
                        x-init="const ctx = $el.querySelector('canvas').getContext('2d');
                        const isDark = document.documentElement.classList.contains('dark');
                        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
                        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');
                        
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Omzet',
                                    data: data,
                                    borderColor: '#6366f1',
                                    backgroundColor: gradient,
                                    borderWidth: 4,
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: '#ffffff',
                                    pointBorderColor: '#6366f1',
                                    pointBorderWidth: 2,
                                    pointRadius: 6,
                                    pointHoverRadius: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e1b4b', padding: 14, titleFont: { size: 13 }, bodyFont: { size: 13 }, displayColors: false, cornerRadius: 10 } },
                                scales: {
                                    y: { beginAtZero: true, grid: { borderDash: [4, 4], color: isDark ? '#374151' : '#f3f4f6' }, ticks: { font: { size: 12, weight: 'bold' }, color: '#9ca3af', padding: 10 } },
                                    x: { grid: { display: false }, ticks: { font: { size: 12 }, color: '#9ca3af', padding: 10 } }
                                }
                            }
                        });">
                        <canvas></canvas>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-900 p-8 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
                <div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Alokasi Biaya</h4>
                    <p class="text-sm text-gray-500 mb-8">Distribusi pengeluaranmu</p>

                    <div class="relative h-56 w-full flex justify-center"
                        wire:key="expense-chart-wrapper-{{ $range }}">
                        <div wire:ignore class="h-full w-full flex justify-center"
                            x-data='{ labels: @json($expenseLabels), data: @json($expenseData) }'
                            x-init="const ctx = $el.querySelector('canvas').getContext('2d');
                            const bgColors = ['#6366f1', '#10b981', '#f59e0b', '#f43f5e', '#a855f7'];
                            const isDummy = (data.length === 1 && labels[0] === 'Belum Ada Pengeluaran');
                            
                            new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: isDummy ? ['Belum ada data'] : labels,
                                    datasets: [{
                                        data: isDummy ? [1] : data,
                                        backgroundColor: isDummy ? ['#f3f4f6'] : bgColors,
                                        borderWidth: 0,
                                        hoverOffset: 10
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '65%',
                                    plugins: { legend: { display: !isDummy, position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 20, font: { size: 12, family: 'sans-serif' } } } }
                                }
                            });">
                            <canvas></canvas>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-6 p-5 bg-gray-50 dark:bg-gray-800 rounded-2xl text-center border border-gray-100 dark:border-gray-700">
                    <span class="text-xs text-gray-500 uppercase font-bold tracking-widest">Total
                        Pengeluaran</span>
                    <p class="text-2xl font-black text-gray-800 dark:text-white mt-1">Rp
                        {{ number_format($expenseThisMonth, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- 4. RECENT TRANSACTIONS (Shadow on Table Container) --}}
        <div
            class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div
                class="p-8 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-white dark:bg-gray-900">
                <div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">Transaksi Terakhir</h4>
                    <p class="text-sm text-gray-500 mt-1">Aktivitas keuangan terbaru</p>
                </div>
                <a href="{{ route('daily-checkin.index') }}"
                    class="group flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 bg-indigo-50 dark:bg-indigo-900/30 px-4 py-2 rounded-xl transition">
                    Lihat Semua
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead
                        class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-800 uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-8 py-5 font-bold">Keterangan</th>
                            <th class="px-8 py-5 font-bold">Tanggal</th>
                            <th class="px-8 py-5 font-bold text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($recentTransactions as $trx)
                            @php
                                $isInflow = $trx->is_inflow;
                                $categoryName = $trx->coa ? $trx->coa->name : ($isInflow ? 'Pemasukan' : 'Pengeluaran');
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition duration-200">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-sm {{ $isInflow ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
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
                                            <p class="font-bold text-gray-900 dark:text-white text-base">
                                                {{ Str::limit($trx->description, 50) }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $categoryName }} •
                                                {{ $trx->payment_method ?? 'Kas' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-gray-600 dark:text-gray-400 font-medium">
                                    {{ $trx->transaction_date->format('d M Y') }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span
                                        class="text-base font-black {{ $isInflow ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $isInflow ? '+' : '-' }} Rp
                                        {{ number_format($trx->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div
                                            class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                <div class="px-8 py-5 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50">
                    {{ $recentTransactions->links() }}
                </div>
            @endif
        </div>

        <a href="{{ route('expenses.create') }}"
            class="md:hidden fixed bottom-6 right-6 w-16 h-16 bg-indigo-600 text-white rounded-full shadow-2xl shadow-indigo-600/50 flex items-center justify-center hover:bg-indigo-700 hover:scale-110 transition z-50">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </a>

    </div>
</div>
</div>
