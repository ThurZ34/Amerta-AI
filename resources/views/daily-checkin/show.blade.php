@extends('layouts.app')

@section('header', 'Detail Laporan Harian')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumb & Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
                        <a href="{{ route('daily-checkin.index') }}"
                            class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            Kalender
                        </a>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="text-gray-900 dark:text-white font-medium">Laporan Detail</span>
                    </nav>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ $dailySale->date->translatedFormat('l, d F Y') }}
                    </h2>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('daily-checkin.edit', $dailySale->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 border border-indigo-600 rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('daily-checkin.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- AI Analysis Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-indigo-100 dark:border-indigo-900/30 overflow-hidden mb-8">
                <div class="p-6 md:p-8 flex flex-col md:flex-row gap-6">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Analisa Amerta AI</h3>
                        <div
                            class="prose prose-sm prose-indigo dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                            {!! \Illuminate\Support\Str::markdown($dailySale->ai_analysis) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Revenue -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Omset</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Rp {{ number_format($dailySale->total_revenue, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Profit -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Profit</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 tracking-tight">
                        +Rp {{ number_format($dailySale->total_profit, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Items Sold -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Produk Terjual</p>
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 tracking-tight">
                        {{ $dailySale->items->sum('quantity') }} <span
                            class="text-sm font-normal text-gray-500 dark:text-gray-400">Unit</span>
                    </p>
                </div>
            </div>

            <!-- Detail Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Rincian Produk</h3>
                    <span
                        class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md">
                        {{ $dailySale->items->count() }} Jenis Produk
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800/50">Nama Produk</th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800/50 text-center">Jumlah</th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800/50 text-right">Harga Satuan</th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800/50 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($dailySale->items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->produk->nama_produk }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-400">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Total
                                    Pendapatan</td>
                                <td class="px-6 py-4 text-right text-lg font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($dailySale->total_revenue, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
