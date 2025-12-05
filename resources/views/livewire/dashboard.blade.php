@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="min-h-screen bg-gray-50/50 dark:bg-gray-900 transition-colors duration-300 font-sans pb-24">

        {{-- Main Container --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            {{-- 1. HEADER & GREETING (Compact) --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                        Dashboard <span class="text-gray-400 dark:text-gray-600 font-medium">Overview</span>
                    </h1>
                </div>
                
                {{-- Quick Action (Optional) --}}
                <div class="flex gap-3">
                    <button class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                        Unduh Laporan
                    </button>
                    <a href="{{ route('kasir.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 dark:shadow-none flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Transaksi Baru
                    </a>
                </div>
            </div>

            {{-- 2. BENTO GRID LAYOUT STARTS HERE --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6">

                {{-- A. STATS ROW (Top - Spanning full width) --}}
                {{-- Menggunakan Grid 4 kolom di dalam span-12 --}}
                <div class="lg:col-span-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Card 1: Saldo --}}
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                             <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Kas</p>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">Rp {{ number_format($cashBalance, 0, ',', '.') }}</h3>
                    </div>

                    {{-- Card 2: Omset --}}
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden group">
                         <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                             <svg class="w-16 h-16 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <div class="flex justify-between items-start relative z-10">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Omset Bulan Ini</p>
                                <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</h3>
                            </div>
                            <span class="text-xs font-bold px-2 py-1 rounded-lg {{ $growthPercentage >= 0 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-rose-100 text-rose-700' }}">
                                {{ $growthPercentage >= 0 ? '+' : '' }}{{ number_format($growthPercentage, 1) }}%
                            </span>
                        </div>
                    </div>

                    {{-- Card 3: Pengeluaran --}}
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                             <svg class="w-16 h-16 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengeluaran</p>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</h3>
                    </div>

                    {{-- Card 4: Profit --}}
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                             <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Profit Bersih</p>
                        <h3 class="text-2xl font-black {{ $profitThisMonth >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-rose-600' }} mt-1">Rp {{ number_format($profitThisMonth, 0, ',', '.') }}</h3>
                    </div>
                </div>

                {{-- B. MAIN CHART (Left - 8 Columns) --}}
                <div class="lg:col-span-8 bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">Analitik Penjualan</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tren pendapatan bisnis Anda</p>
                        </div>
                        <select wire:model.live="range" class="bg-gray-50 dark:bg-gray-700 border-none text-sm font-semibold rounded-lg text-gray-600 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 py-2 px-3">
                            <option value="day">Hari Ini</option>
                            <option value="week">Minggu Ini</option>
                            <option value="month">Bulan Ini</option>
                            <option value="year">Tahun Ini</option>
                        </select>
                    </div>
                    
                    {{-- Canvas Container --}}
                    <div class="relative h-[300px] w-full" wire:key="sales-chart-wrapper-{{ $range }}">
                         {{-- (Chart JS Code sama seperti sebelumnya, letakkan disini) --}}
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
                                        label: 'Penjualan (Rp)',
                                        data: data,
                                        borderColor: '#6366f1',
                                        backgroundColor: gradient,
                                        fill: true,
                                        tension: 0.4,
                                        pointBackgroundColor: '#6366f1',
                                        pointBorderColor: '#fff',
                                        pointBorderWidth: 2,
                                        pointRadius: 0, 
                                        pointHoverRadius: 6
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { display: false } },
                                    scales: {
                                        y: { beginAtZero: true, grid: { borderDash: [4, 4], color: isDark ? '#374151' : '#f3f4f6' }, ticks: { font: { size: 10 }, color: '#9ca3af' } },
                                        x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#9ca3af', maxTicksLimit: 7 } }
                                    }
                                }
                            });">
                            <canvas></canvas>
                        </div>
                    </div>
                </div>

                {{-- C. SIDEBAR (Right - 4 Columns) --}}
                <div class="lg:col-span-4 flex flex-col gap-6">
                    
                    {{-- BUSINESS HEALTH CARD (Redesigned) --}}
                    @php
                        // Mapping warna background gradient berdasarkan status
                        $statusKey = $businessHealth['statusColor'] ?? 'gray';
                        $bgGradient = match($statusKey) {
                            'emerald' => 'from-emerald-500 to-teal-600',
                            'amber' => 'from-amber-500 to-orange-600',
                            'rose' => 'from-rose-500 to-pink-600',
                            default => 'from-gray-500 to-gray-600',
                        };
                        $score = $businessHealth['score'] ?? 0;
                    @endphp

                    <div x-data="{ showModal: false }" class="relative rounded-3xl p-6 text-white shadow-xl overflow-hidden group cursor-pointer transition-all hover:shadow-2xl hover:-translate-y-1 bg-gradient-to-br {{ $bgGradient }}"
                        @click="showModal = true">
                        
                        {{-- Decorative background circles --}}
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10 blur-2xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-black opacity-10 blur-2xl"></div>

                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-white/80 text-sm font-semibold uppercase tracking-wider">Kesehatan Bisnis</p>
                                    <h3 class="text-3xl font-black mt-1">{{ $businessHealth['status'] ?? 'N/A' }}</h3>
                                </div>
                                <div class="bg-white/20 backdrop-blur-md rounded-full w-12 h-12 flex items-center justify-center border border-white/30">
                                    <span class="font-bold text-lg">{{ $score }}</span>
                                </div>
                            </div>

                            <p class="text-white/90 text-sm line-clamp-2 leading-relaxed mb-6">
                                "{{ Str::limit($businessHealth['message'] ?? 'Analisis belum tersedia.', 80) }}"
                            </p>

                            {{-- Button 'Lihat' yang lebih menarik --}}
                            <div class="flex items-center gap-2 group-hover:gap-4 transition-all duration-300">
                                <span class="text-sm font-bold">Lihat Analisis Lengkap</span>
                                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL (Include modal code here, same as before but style matched) --}}
                        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
                             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
                             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                             @click.self="showModal = false">
                             <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                             <div class="relative bg-white dark:bg-gray-800 rounded-3xl p-8 max-w-lg w-full shadow-2xl" @click.stop>
                                {{-- Isi Modal sama seperti sebelumnya --}}
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Detail Kesehatan Bisnis</h3>
                                <p class="text-gray-500 mb-4">Analisis mendalam performa bulan ini.</p>
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl mb-4">
                                     <p class="text-gray-800 dark:text-gray-200">{{ $businessHealth['message'] }}</p>
                                </div>
                                <div class="flex justify-end">
                                    <button @click="showModal = false" class="px-6 py-2 bg-gray-900 dark:bg-gray-600 text-white rounded-xl">Tutup</button>
                                </div>
                             </div>
                        </div>
                    </div>

                    {{-- EXPENSE CHART (Mini) --}}
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm flex-1 flex flex-col justify-center items-center relative">
                         <h5 class="absolute top-5 left-5 text-sm font-bold text-gray-500 dark:text-gray-400">Alokasi Biaya</h5>
                         <div class="h-32 w-32 mt-4 relative">
                              {{-- Chart Doughnut Code Here --}}
                               <div wire:ignore class="h-full w-full"
                                x-data='{ labels: @json($expenseLabels), data: @json($expenseData) }'
                                x-init="const ctx = $el.querySelector('canvas').getContext('2d');
                                const bgColors = ['#6366f1', '#10b981', '#f59e0b', '#f43f5e', '#a855f7'];
                                const isDummy = (data.length === 1 && labels[0] === 'Belum Ada Pengeluaran');
                                new Chart(ctx, { type: 'doughnut', data: { labels: isDummy ? ['Belum ada data'] : labels, datasets: [{ data: isDummy ? [1] : data, backgroundColor: isDummy ? ['#f3f4f6'] : bgColors, borderWidth: 0 }] }, options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false } } } });">
                                <canvas></canvas>
                            </div>
                         </div>
                         <div class="mt-4 text-center">
                             <span class="text-xs text-gray-400 uppercase">Total</span>
                             <p class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</p>
                         </div>
                    </div>

                </div>

                {{-- D. TRANSACTIONS (Bottom - 12 Columns) --}}
                <div class="lg:col-span-12 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                     {{-- (Table code sama seperti sebelumnya, tapi header bisa dibuat lebih simple) --}}
                     <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h4 class="font-bold text-lg text-gray-900 dark:text-white">Transaksi Terakhir</h4>
                        <a href="{{ route('riwayat.index') }}" class="text-indigo-600 text-sm font-semibold hover:underline">Lihat Semua</a>
                     </div>
                     <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50/50 dark:bg-gray-700/30 text-xs uppercase text-gray-400 font-semibold">
                                <tr>
                                    <th class="px-6 py-4">Keterangan</th>
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4 text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($recentTransactions as $trx)
                                    {{-- (Loop isi tabel sama seperti sebelumnya) --}}
                                    <tr>
                                         <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ Str::limit($trx->description, 40) }}</td>
                                         <td class="px-6 py-4 text-gray-500">{{ $trx->transaction_date->format('d M Y') }}</td>
                                         <td class="px-6 py-4 text-right font-bold {{ $trx->is_inflow ? 'text-emerald-600' : 'text-rose-600' }}">
                                             {{ $trx->is_inflow ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                         </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                     </div>
                </div>

            </div> {{-- End Grid --}}

        </div>
    </div>
@endsection