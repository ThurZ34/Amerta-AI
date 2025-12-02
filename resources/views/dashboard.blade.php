@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="h-full w-full flex flex-col overflow-hidden bg-gray-50 dark:bg-gray-950 transition-colors duration-300">

        <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6 custom-scrollbar">

            <div
                class="bg-linear-to-r from-indigo-600 to-purple-600 rounded-xl p-6 text-white shadow-lg relative overflow-hidden group cursor-pointer transition-transform active:scale-[0.99] border border-transparent dark:border-indigo-500/30">
                <div class="relative z-10 flex items-start gap-4">
                    <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm animate-pulse">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Analisa Keuangan Hari Ini</h3>
                        <p class="text-indigo-100 mt-1 text-sm leading-relaxed max-w-2xl">
                            "Bos, performa minggu ini bagus! Omzet naik 15% dibanding minggu lalu. Tapi hati-hati,
                            pengeluaran bahan baku agak bengkak di hari Selasa. Saldo kas saat ini aman untuk operasional 14
                            hari ke depan."
                        </p>
                    </div>
                </div>
                <div
                    class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-white/10 blur-xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="absolute bottom-0 right-10 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Kas Ditangan</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">Rp 5.450.000</h3>
                    <div class="mt-4 flex items-center text-xs text-gray-400 dark:text-gray-500">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                        Update: Baru saja
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Omzet Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">Rp 12.800.000</h3>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +12% dr bulan lalu
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengeluaran</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">Rp 4.200.000</h3>
                    <p class="text-xs text-rose-500 dark:text-rose-400 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                        Lebih hemat 5%
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition relative overflow-hidden group hover:-translate-y-1">
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Estimasi Laba Bersih</p>
                        <h3 class="text-2xl font-bold text-indigo-900 dark:text-white mt-2">Rp 8.600.000</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Margin keuntungan: 67%</p>
                    </div>
                    <div
                        class="absolute right-0 bottom-0 opacity-10 dark:opacity-20 transform translate-x-2 translate-y-2 group-hover:scale-110 transition">
                        <svg class="w-20 h-20 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div
                    class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm transition-colors duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h4 class="font-bold text-gray-800 dark:text-white">Tren Penjualan</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Performa 7 hari terakhir</p>
                        </div>
                        <select
                            class="text-sm border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option>7 Hari Terakhir</option>
                            <option>30 Hari Terakhir</option>
                        </select>
                    </div>
                    <div class="relative h-64 w-full" x-data="{ mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }" x-init="const ctx = $el.querySelector('canvas').getContext('2d');

                    // Setup Warna sesuai mode
                    const isDark = document.documentElement.classList.contains('dark');
                    const gridColor = isDark ? '#374151' : '#f3f4f6';
                    const textColor = isDark ? '#9ca3af' : '#6b7280';

                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.3)');
                    gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                            datasets: [{
                                label: 'Omzet',
                                data: [150000, 230000, 180000, 320000, 290000, 450000, 500000],
                                borderColor: '#4F46E5',
                                backgroundColor: gradient,
                                borderWidth: 3,
                                tension: 0.5,
                                fill: true,
                                pointBackgroundColor: isDark ? '#111827' : '#ffffff',
                                pointBorderColor: '#4F46E5',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 12,
                                pointHoverBorderWidth: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: isDark ? 'rgba(255,255,255,0.9)' : 'rgba(0,0,0,0.8)',
                                    titleColor: isDark ? '#000' : '#fff',
                                    bodyColor: isDark ? '#000' : '#fff',
                                    padding: 10,
                                    cornerRadius: 8,
                                    displayColors: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { borderDash: [4, 4], color: gridColor },
                                    border: { display: false },
                                    ticks: { color: textColor }
                                },
                                x: {
                                    grid: { display: false },
                                    border: { display: false },
                                    ticks: { color: textColor }
                                }
                            },
                            animations: {
                                tension: { duration: 2000, easing: 'linear', from: 0.55, to: 0.45, loop: true },
                                y: { duration: 1500, easing: 'easeOutElastic', from: 200 }
                            }
                        }
                    });">
                        <canvas></canvas>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm transition-colors duration-300">
                    <h4 class="font-bold text-gray-800 dark:text-white mb-2">Alokasi Biaya</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Kemana uang habis?</p>

                    <div class="relative h-48 w-full flex justify-center" x-data x-init="const isDark = document.documentElement.classList.contains('dark');
                    const textColor = isDark ? '#e5e7eb' : '#374151';

                    new Chart($el.querySelector('canvas').getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Bahan Baku', 'Gaji', 'Listrik/Air', 'Lainnya'],
                            datasets: [{
                                data: [45, 25, 15, 15],
                                backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#F43F5E'],
                                borderWidth: 0,
                                hoverOffset: 20
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 15,
                                        color: textColor
                                    }
                                }
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true,
                                duration: 2000,
                                easing: 'easeOutBounce'
                            }
                        }
                    });">
                        <canvas></canvas>
                    </div>
                    <div class="text-center mt-4">
                        <p class="text-xs text-gray-400 dark:text-gray-500">Total Pengeluaran: <strong
                                class="text-gray-700 dark:text-gray-300">Rp 4.2jt</strong></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div
                    class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden transition-colors duration-300">
                    <div
                        class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-gray-800/50">
                        <h4 class="font-bold text-gray-800 dark:text-white">Transaksi Terakhir</h4>
                        <a href="#" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Lihat
                            Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3 font-medium text-xs uppercase">Keterangan</th>
                                    <th class="px-4 py-3 font-medium text-xs uppercase">Tgl</th>
                                    <th class="px-4 py-3 font-medium text-xs uppercase text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-800 dark:text-gray-200">Jual 50 Porsi Bakso</p>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300">Penjualan</span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">Hari Ini</td>
                                    <td class="px-4 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400">+ Rp
                                        750.000</td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-800 dark:text-gray-200">Beli Gas 3kg (5 tabung)</p>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-rose-100 dark:bg-rose-900/50 text-rose-800 dark:text-rose-300">Operasional</span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">Kemarin</td>
                                    <td class="px-4 py-3 text-right font-bold text-rose-600 dark:text-rose-400">- Rp
                                        100.000</td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-800 dark:text-gray-200">Beli Daging Sapi 2kg</p>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-rose-100 dark:bg-rose-900/50 text-rose-800 dark:text-rose-300">Bahan
                                            Baku</span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">Kemarin</td>
                                    <td class="px-4 py-3 text-right font-bold text-rose-600 dark:text-rose-400">- Rp
                                        240.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <button
                class="md:hidden fixed bottom-6 right-6 bg-indigo-600 text-white p-4 rounded-full shadow-lg hover:bg-indigo-700 transition z-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </button>

        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #4b5563;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }
    </style>
@endsection
