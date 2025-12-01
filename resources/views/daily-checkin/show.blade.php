@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Hasil Analisa Harian</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Tanggal:
                        {{ $dailySale->date->translatedFormat('l, d F Y') }}</p>
                </div>
                <a href="{{ route('daily-checkin.index') }}"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    &larr; Kembali ke Kalender
                </a>
            </div>

            <!-- AI Feedback Card -->
            <div
                class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl shadow-xl overflow-hidden mb-8 text-white relative">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>

                <div class="p-8 relative z-10">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Kata Amerta AI</h3>
                            <div class="prose prose-invert max-w-none text-indigo-50 leading-relaxed">
                                {!! \Illuminate\Support\Str::markdown($dailySale->ai_analysis) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Omset</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Rp
                        {{ number_format($dailySale->total_revenue, 0, ',', '.') }}</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Profit</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">+Rp
                        {{ number_format($dailySale->total_profit, 0, ',', '.') }}</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Produk Terjual
                    </p>
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">
                        {{ $dailySale->items->sum('quantity') }} Unit</p>
                </div>
            </div>

            <!-- Detailed Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <h3 class="font-bold text-gray-900 dark:text-white">Rincian Penjualan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50/50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                                <th class="px-6 py-3 font-medium">Produk</th>
                                <th class="px-6 py-3 font-medium text-center">Qty</th>
                                <th class="px-6 py-3 font-medium text-right">Harga Satuan</th>
                                <th class="px-6 py-3 font-medium text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($dailySale->items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $item->produk->nama_produk }}</td>
                                    <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-300">{{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-600 dark:text-gray-300">Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">Rp
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
