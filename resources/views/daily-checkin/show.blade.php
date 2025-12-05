@extends('layouts.app')

@section('header', 'Detail Laporan Harian')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 pb-40" x-data="{ editModalOpen: false, detailModalOpen: false, isLoading: false }">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('daily-checkin.index') }}"
                            class="group p-2 -ml-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                            title="Kembali ke Kalender">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>

                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ $dailySale->date->translatedFormat('l, d F Y') }}
                        </h2>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button @click="detailModalOpen = true" type="button"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Lihat Rincian
                    </button>

                    <button @click="editModalOpen = true" type="button"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 border border-indigo-600 rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Laporan
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-indigo-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Omset</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Rp {{ number_format($dailySale->total_revenue, 0, ',', '.') }}
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-green-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Profit Kotor</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 tracking-tight">
                        +Rp {{ number_format($dailySale->total_profit, 0, ',', '.') }}
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-blue-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Produk Terjual</p>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 tracking-tight">
                        {{ $dailySale->items->sum('quantity') }} <span
                            class="text-sm font-normal text-gray-500 dark:text-gray-400">Unit</span>
                    </p>
                </div>
            </div>

            <div
                class="bg-slate-900 text-white rounded-3xl p-8 mb-24 shadow-xl border border-slate-700 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-500 rounded-full blur-3xl opacity-20">
                </div>
                <div
                    class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-purple-500 rounded-full blur-3xl opacity-20">
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-700/50">
                        <div
                            class="w-10 h-10 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Analisa Amerta AI</h3>
                            <p class="text-slate-400 text-sm">Insight cerdas berdasarkan performa penjualan Anda hari ini.
                            </p>
                        </div>
                    </div>

                    <div
                        class="prose prose-invert max-w-none md:columns-2 gap-12 prose-p:leading-relaxed prose-li:marker:text-indigo-400">
                        {!! \Illuminate\Support\Str::markdown($dailySale->ai_analysis) !!}
                    </div>
                </div>
            </div>

        </div>

        <div x-show="detailModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-detail-title" role="dialog" aria-modal="true" x-cloak>

            <div x-show="detailModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" @click="detailModalOpen = false">
            </div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="detailModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-slate-900 border border-slate-700 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">

                    <div
                        class="px-4 py-4 sm:px-6 bg-slate-800/50 border-b border-slate-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold leading-6 text-white" id="modal-detail-title">
                            Rincian Produk
                        </h3>
                        <button @click="detailModalOpen = false"
                            class="text-slate-400 hover:text-white focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-4 py-6 sm:px-6">
                        <div class="bg-slate-900 rounded-xl overflow-hidden shadow-inner border border-slate-800">
                            <div
                                class="px-6 py-4 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
                                <span class="text-sm text-slate-400">Daftar item yang terjual pada tanggal ini</span>
                                <span
                                    class="text-xs font-semibold text-slate-300 bg-slate-700 px-3 py-1 rounded-full border border-slate-600">
                                    {{ $dailySale->items->count() }} Jenis Produk
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left whitespace-nowrap">
                                    <thead>
                                        <tr
                                            class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                                            <th class="px-6 py-4 bg-slate-800/30">Nama Produk</th>
                                            <th class="px-6 py-4 bg-slate-800/30 text-center">Jumlah</th>
                                            <th class="px-6 py-4 bg-slate-800/30 text-right">Harga Satuan</th>
                                            <th class="px-6 py-4 bg-slate-800/30 text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800">
                                        @foreach ($dailySale->items as $item)
                                            <tr class="hover:bg-slate-800/50 transition-colors group">
                                                <td class="px-6 py-4">
                                                    <div
                                                        class="text-sm font-medium text-white group-hover:text-indigo-300 transition-colors">
                                                        {{ $item->produk->nama_produk }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                                                        {{ $item->quantity }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right text-sm text-slate-400">
                                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-white">
                                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-slate-800/80 border-t border-slate-700">
                                        <tr>
                                            <td colspan="3"
                                                class="px-6 py-5 text-right text-sm font-medium text-slate-400">Total
                                                Pendapatan</td>
                                            <td class="px-6 py-5 text-right text-xl font-bold text-white">
                                                Rp {{ number_format($dailySale->total_revenue, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-4 sm:px-6 bg-slate-800/50 border-t border-slate-700 sm:flex sm:flex-row-reverse">
                    </div>
                </div>
            </div>
        </div>

        <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>

            <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" @click="editModalOpen = false">
            </div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="editModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-200 dark:border-gray-700">

                    <div
                        class="px-4 py-4 sm:px-6 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white" id="modal-title">
                                Edit Penjualan
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $dailySale->date->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                        <button @click="editModalOpen = false"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('daily-checkin.update', $dailySale->id) }}" method="POST"
                        @submit="isLoading = true">
                        @csrf
                        @method('PUT')

                        <div class="px-4 py-6 sm:px-6 max-h-[60vh] overflow-y-auto bg-gray-50/50 dark:bg-gray-900">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse ($produks as $produk)
                                    @php
                                        $existingItem = $dailySale->items->where('produk_id', $produk->id)->first();
                                        $existingQty = $existingItem ? $existingItem->quantity : 0;
                                    @endphp
                                    <div x-data="{ count: {{ $existingQty }} }"
                                        class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-500 transition-all duration-300 p-3 flex gap-4 items-center">
                                        <div
                                            class="w-16 h-16 shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden relative">
                                            @if ($produk->gambar)
                                                <img src="{{ asset('storage/' . $produk->gambar) }}"
                                                    alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0 flex flex-col justify-between h-16 py-0.5">
                                            <div>
                                                <h3 class="font-bold text-sm text-gray-900 dark:text-white leading-tight truncate"
                                                    title="{{ $produk->nama_produk }}">
                                                    {{ $produk->nama_produk }}
                                                </h3>
                                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">
                                                    Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <div class="flex items-center justify-between mt-auto">
                                                <div
                                                    class="flex items-center bg-gray-50 dark:bg-gray-700/50 rounded-lg p-0.5 border border-gray-200 dark:border-gray-600">
                                                    <button type="button" @click="if(count > 0) count--"
                                                        class="w-6 h-6 flex items-center justify-center rounded-md text-gray-500 hover:bg-white dark:hover:bg-gray-600 hover:text-indigo-600 dark:hover:text-indigo-400 hover:shadow-sm transition-all disabled:opacity-50">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M20 12H4" />
                                                        </svg>
                                                    </button>
                                                    <input type="number" name="sales[{{ $produk->id }}]"
                                                        x-model="count" min="0"
                                                        class="w-8 text-center bg-transparent border-none p-0 text-xs font-bold text-gray-800 dark:text-white focus:ring-0 appearance-none [-moz-appearance:textfield] [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none">
                                                    <button type="button" @click="count++"
                                                        class="w-6 h-6 flex items-center justify-center rounded-md text-gray-500 hover:bg-white dark:hover:bg-gray-600 hover:text-indigo-600 dark:hover:text-indigo-400 hover:shadow-sm transition-all">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full py-8 flex flex-col items-center justify-center text-center">
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada data produk di
                                            katalog.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div
                            class="px-4 py-4 sm:px-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700 sm:flex sm:flex-row-reverse gap-3">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Update & Analisa Ulang
                            </button>
                            <button type="button" @click="editModalOpen = false"
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="isLoading"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm transition-opacity"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;" x-cloak>
            <div class="text-center">
                <div
                    class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-indigo-500 border-t-transparent mb-4">
                </div>
                <h3 class="text-xl font-bold text-white tracking-wide">Mengupdate Analisis...</h3>
                <p class="text-gray-300 mt-2 text-sm font-light">Mohon tunggu sebentar.</p>
            </div>
        </div>

    </div>
@endsection
