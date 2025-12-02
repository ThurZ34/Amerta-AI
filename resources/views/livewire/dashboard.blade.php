<div>
    @section('header', 'Dashboard')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="h-full w-full flex flex-col overflow-hidden bg-gray-50 dark:bg-gray-950 transition-colors duration-300">

        <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6 custom-scrollbar">

            {{-- KARTU ANALISA --}}
            <div class="bg-linear-to-r from-indigo-600 to-purple-600 rounded-xl p-6 text-white shadow-lg relative overflow-hidden group">
                <div class="relative z-10 flex items-start gap-4">
                    <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm animate-pulse">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Ringkasan Keuangan</h3>
                        <p class="text-indigo-100 mt-1 text-sm leading-relaxed max-w-2xl">
                            "{{ $aiMessage }}"
                        </p>
                    </div>
                </div>
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-white/10 blur-xl"></div>
            </div>

            {{-- GRID RINGKASAN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Saldo --}}
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Kas</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">Rp {{ number_format($cashBalance, 0, ',', '.') }}</h3>
                </div>
                {{-- Omset --}}
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Omzet Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</h3>
                    <p class="text-xs {{ $growthPercentage >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-2">
                        {{ $growthPercentage >= 0 ? '+' : '' }}{{ number_format($growthPercentage, 1) }}% dr bulan lalu
                    </p>
                </div>
                {{-- Pengeluaran --}}
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengeluaran</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</h3>
                </div>
                {{-- Profit --}}
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Laba Bersih</p>
                    <h3 class="text-2xl font-bold {{ $profitThisMonth >= 0 ? 'text-indigo-900 dark:text-white' : 'text-rose-600' }} mt-2">
                        Rp {{ number_format($profitThisMonth, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- CHART TREN (Dinamis Livewire) --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="font-bold text-gray-800 dark:text-white">Tren Penjualan</h4>

                        {{-- DROPDOWN FILTER LIVEWIRE --}}
                        <select wire:model.live="range" class="text-sm border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer py-1.5 pl-3 pr-8">
                            <option value="day">Hari Ini</option>
                            <option value="week">1 Minggu</option>
                            <option value="month">1 Bulan</option>
                            <option value="year">1 Tahun</option>
                            <option value="decade">1 Dekade</option>
                        </select>
                    </div>

                    {{-- CHART CONTAINER FIX --}}
                    {{-- Wrapper Luar: Menggunakan wire:key agar saat $range berubah, elemen ini diganti total --}}
                    <div class="relative h-64 w-full" wire:key="sales-chart-wrapper-{{ $range }}">
                         {{-- Container Dalam: Menggunakan wire:ignore agar Livewire TIDAK menyentuh isinya saat update lain (seperti pagination) --}}
                         <div wire:ignore class="h-full w-full"
                             x-data='{
                                labels: @json($chartLabels),
                                data: @json($chartData)
                             }'
                             x-init="
                                const ctx = $el.querySelector('canvas').getContext('2d');
                                const isDark = document.documentElement.classList.contains('dark');
                                const gridColor = isDark ? '#374151' : '#f3f4f6';
                                const textColor = isDark ? '#9ca3af' : '#6b7280';
                                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                                gradient.addColorStop(0, 'rgba(79, 70, 229, 0.3)');
                                gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

                                new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Omzet',
                                            data: data,
                                            borderColor: '#4F46E5',
                                            backgroundColor: gradient,
                                            borderWidth: 3,
                                            tension: 0,
                                            fill: true,
                                            pointBackgroundColor: isDark ? '#111827' : '#ffffff',
                                            pointBorderColor: '#4F46E5',
                                            pointBorderWidth: 2,
                                            pointRadius: 4
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        interaction: {
                                            mode: 'index',
                                            intersect: false
                                        },
                                        animations: {
                                            duration: 1500,
                                            easing: 'easeInOutCubic',
                                            x: { from: 0 },
                                            y: { from: 0 }
                                        },
                                        plugins: { legend: { display: false } },
                                        scales: {
                                            y: { beginAtZero: true, grid: { borderDash: [4,4], color: gridColor }, ticks: { color: textColor } },
                                            x: { grid: { display: false }, ticks: { color: textColor } }
                                        }
                                    }
                                });
                             ">
                            <canvas></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <h4 class="font-bold text-gray-800 dark:text-white mb-2">Alokasi Biaya</h4>
                    <p class="text-sm text-gray-500 mb-6">Top 5 Pengeluaran Bulan Ini</p>

                    <div class="relative h-48 w-full flex justify-center" wire:key="expense-chart-wrapper-{{ $range }}">
                        <div wire:ignore class="h-full w-full flex justify-center"
                             x-data='{
                                labels: @json($expenseLabels),
                                data: @json($expenseData)
                             }'
                             x-init="
                                const ctx = $el.querySelector('canvas').getContext('2d');
                                const isDark = document.documentElement.classList.contains('dark');
                                const textColor = isDark ? '#e5e7eb' : '#374151';
                                const bgColors = ['#4F46E5', '#10B981', '#F59E0B', '#F43F5E', '#A855F7'];

                                const isDummyData = (data.length === 1 && labels[0] === 'Belum Ada Pengeluaran');

                                new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: isDummyData ? ['Belum ada Pengeluaran'] : labels,
                                        datasets: [{
                                            data: isDummyData ? [1] : data,
                                            backgroundColor: isDummyData ? ['#E5E7EB'] : bgColors,
                                            borderWidth: 0
                                        }]
                                    },
                                    options: {
                                        responsive: true, maintainAspectRatio: false, cutout: '75%',
                                        plugins: {
                                            legend: {
                                                display: !isDummyData,
                                                position: 'bottom',
                                                labels: { usePointStyle: true, padding: 15, color: textColor, font: { size: 10 } }
                                            },
                                            tooltip: { enabled: !isDummyData }
                                        }
                                    }
                                });
                             ">
                            <canvas></canvas>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-xs text-gray-400 dark:text-gray-500">Total Pengeluaran: <strong
                            class="text-gray-700 dark:text-gray-300">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</strong></p>
                    </div>
                </div>
            </div>

            {{-- TABEL TRANSAKSI --}}
            <div class="grid grid-cols-1 gap-6 mt-6">
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-gray-800/50">
                        <h4 class="font-bold text-gray-800 dark:text-white">Riwayat Transaksi</h4>
                        <a href="{{ route('daily-checkin.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Kalender Harian</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3 font-medium uppercase">Keterangan</th>
                                    <th class="px-4 py-3 font-medium uppercase">Tgl</th>
                                    <th class="px-4 py-3 font-medium uppercase text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                @forelse ($recentTransactions as $trx)
                                    @php
                                        $isInflow = $trx->is_inflow;
                                        $amountFormatted = number_format($trx->amount, 0, ',', '.');
                                        $colorClass = $isInflow ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400';
                                        $sign = $isInflow ? '+' : '-';
                                        $badgeClass = $isInflow ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300';
                                        $categoryName = $trx->coa ? $trx->coa->name : ($isInflow ? 'Pemasukan' : 'Pengeluaran');
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col">
                                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ Str::limit($trx->description, 50) }}</span>
                                                <span class="text-[10px] text-gray-400">{{ $categoryName }} • {{ $trx->payment_method ?? 'Kas' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
                                            {{ $trx->transaction_date->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold {{ $colorClass }} whitespace-nowrap">
                                            {{ $sign }} Rp {{ $amountFormatted }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">Belum ada transaksi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION LINKS --}}
                    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">
                        {{ $recentTransactions->links() }}
                    </div>
                </div>
            </div>

            {{-- TOMBOL FLOAT --}}
            <a href="{{ route('expenses.create') }}" class="fixed bottom-24 right-6 bg-green-600 text-white p-4 rounded-full shadow-lg hover:bg-green-700 transition z-40 md:hidden flex items-center justify-center w-14 h-14">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </a>

        </div>
    </div>
</div>
