@extends('layouts.app')

@section('header', 'Analisa Penjualan Anda')

@section('content')
    <div class="min-h-screen bg-gray-50/50 dark:bg-gray-950 py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Analisa Harian
                    </h2>
                    <div class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>{{ $dailySale->date->translatedFormat('l, d F Y') }}</span>
                    </div>
                </div>
                <a href="{{ route('daily-checkin.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:text-indigo-600 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:text-indigo-400 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                        </path>
                    </svg>
                    Kembali ke Kalender
                </a>
            </div>

            <div class="relative group mb-10">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl opacity-20 blur group-hover:opacity-40 transition duration-1000">
                </div>
                
                <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/50 dark:to-purple-900/50 rounded-2xl flex items-center justify-center ring-1 ring-inset ring-indigo-500/10">
                                <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-3">
                                Kata Amerta AI
                                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10 dark:bg-indigo-400/10 dark:text-indigo-400 dark:ring-indigo-400/20">Analysis</span>
                            </h3>
                            <div class="prose prose-sm sm:prose-base prose-indigo dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed">
                                {!! \Illuminate\Support\Str::markdown($dailySale->ai_analysis) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-700/60 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] dark:shadow-none hover:border-indigo-500/30 transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Omset</p>
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white tabular-nums tracking-tight">
                        Rp {{ number_format($dailySale->total_revenue, 0, ',', '.') }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-700/60 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] dark:shadow-none hover:border-green-500/30 transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Profit</p>
                        <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 tabular-nums tracking-tight">
                        +Rp {{ number_format($dailySale->total_profit, 0, ',', '.') }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-700/60 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] dark:shadow-none hover:border-purple-500/30 transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk Terjual</p>
                        <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white tabular-nums tracking-tight">
                        {{ $dailySale->items->sum('quantity') }} <span class="text-base font-normal text-gray-500">Unit</span>
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Rincian Penjualan</h3>
                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        {{ $dailySale->items->count() }} Items
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                                <th class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50">Produk</th>
                                <th class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 text-center">Qty</th>
                                <th class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 text-right">Harga Satuan</th>
                                <th class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($dailySale->items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->produk->nama_produk }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-400 tabular-nums">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900 dark:text-white tabular-nums">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50/50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Total Keseluruhan</td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white tabular-nums">
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