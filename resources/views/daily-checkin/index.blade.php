@extends('layouts.app')

@section('header', 'Laporan Penjualan Harian')

@section('content')
    <div class="p-4 sm:p-6 lg:p-8 h-full" x-data="{
        createModalOpen: false,
        targetDate: '',
        targetDateDisplay: '',
        isLoading: false,
        salesData: {},
        baseAction: '{{ route('daily-checkin.store') }}',
        formAction: '{{ route('daily-checkin.store') }}',
        method: 'POST',
    
        init() {
            this.resetSalesData();
        },
    
        resetSalesData() {
            this.salesData = {
                @foreach ($produks as $produk)
                        {{ $produk->id }}: 0, @endforeach
            };
        },
    
        openInputModal(dateYmd, dateReadable, existingItems = null, id = null) {
            this.targetDate = dateYmd;
            this.targetDateDisplay = dateReadable;
    
            if (existingItems && id) {
                // Update Mode (including Draft from Kasir)
                this.resetSalesData();
                // Merge existing items
                for (const [productId, qty] of Object.entries(existingItems)) {
                    this.salesData[productId] = parseInt(qty);
                }
                this.formAction = '{{ url('/daily-checkin') }}/' + id;
                this.method = 'PUT';
            } else {
                // Create Mode
                this.resetSalesData();
                this.formAction = this.baseAction;
                this.method = 'POST';
            }
    
            this.createModalOpen = true;
        },
    
        closeModal() {
            this.createModalOpen = false;
        }
    }">

        @php
            $monthlyRevenue = collect($dailySales)->sum('total_revenue');
            $monthlyProfit = collect($dailySales)->sum('total_profit');
            $daysFilled = collect($dailySales)->count();
            $bestDay = collect($dailySales)->sortByDesc('total_revenue')->first();

            $cards = [
                [
                    'title' => 'Total Omset',
                    'value' => 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'),
                    'desc' => $daysFilled . ' Hari Data Terisi',
                    'bg_icon' => 'bg-blue-50 dark:bg-blue-900/20',
                    'text_color' => 'text-blue-600 dark:text-blue-400',
                    'border' => 'border-l-4 border-blue-500',
                ],
                [
                    'title' => 'Profit Kotor (Gross)',
                    'value' => '+Rp ' . number_format($monthlyProfit, 0, ',', '.'),
                    'desc' =>
                        'Margin Produk: ' .
                        ($monthlyRevenue > 0 ? round(($monthlyProfit / $monthlyRevenue) * 100, 1) : 0) .
                        '%',
                    'bg_icon' => 'bg-green-50 dark:bg-green-900/20',
                    'text_color' => 'text-green-600 dark:text-green-400',
                    'border' => 'border-l-4 border-green-500',
                ],
                [
                    'title' => 'Rekor Harian',
                    'value' => $bestDay ? 'Rp ' . number_format($bestDay->total_revenue, 0, ',', '.') : '-',
                    'desc' => $bestDay
                        ? \Carbon\Carbon::parse($bestDay->date)->translatedFormat('d M Y')
                        : 'Belum ada data',
                    'bg_icon' => 'bg-purple-50 dark:bg-purple-900/20',
                    'text_color' => 'text-purple-600 dark:text-purple-400',
                    'border' => 'border-l-4 border-purple-500',
                ],
            ];
        @endphp

        <div x-show="isLoading"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm transition-opacity"
            style="display: none;" x-cloak>
            <div class="text-center">
                <div
                    class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-indigo-500 border-t-transparent mb-4">
                </div>
                <h3 class="text-xl font-bold text-white tracking-wide">Menganalisa Data...</h3>
                <p class="text-gray-300 mt-2 text-sm font-light">Amerta sedang memproses laporan Anda.</p>
            </div>
        </div>

        <div class="flex flex-col xl:flex-row gap-6 h-full">

            <div class="flex-1 order-2 xl:order-1 flex flex-col h-full">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Kalender Penjualan</h2>
                    <div
                        class="flex items-center bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-1">
                        <a href="{{ route('daily-checkin.index', ['date' => $startOfMonth->copy()->subMonth()->format('Y-m-d')]) }}"
                            class="p-1.5 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md text-gray-500 dark:text-gray-400 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <span class="px-4 font-semibold text-gray-900 dark:text-white text-sm min-w-[140px] text-center">
                            {{ $startOfMonth->translatedFormat('F Y') }}
                        </span>
                        <a href="{{ route('daily-checkin.index', ['date' => $startOfMonth->copy()->addMonth()->format('Y-m-d')]) }}"
                            class="p-1.5 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md text-gray-500 dark:text-gray-400 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex-1 flex flex-col">
                    <div
                        class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                        @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                            <div
                                class="py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ $day }}
                            </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-7 bg-gray-200 dark:bg-gray-700 gap-px flex-1">
                        @php
                            $currentDate = $startOfMonth->copy()->startOfWeek();
                            $lastDate = $endOfMonth->copy()->endOfWeek();
                        @endphp

                        @while ($currentDate <= $lastDate)
                            @php
                                $isCurrentMonth = $currentDate->month === $startOfMonth->month;
                                $dateStr = $currentDate->format('Y-m-d');
                                $dateReadable = $currentDate->translatedFormat('l, d F Y');
                                $dailySale = $dailySales[$dateStr] ?? null;
                                $isToday = $currentDate->isToday();
                            @endphp

                            <div
                                class="min-h-[100px] bg-white dark:bg-gray-800 p-2 relative group flex flex-col transition-colors
                                {{ !$isCurrentMonth ? 'bg-gray-50/50 dark:bg-gray-900/50' : 'hover:bg-gray-50 dark:hover:bg-gray-750' }}">

                                @if ($dailySale)
                                    @php
                                        $isPending = in_array($dailySale->ai_analysis, [
                                            'Menunggu analisis...',
                                            'Analyzing...',
                                        ]);
                                        $existingItems = $dailySale->items->pluck('quantity', 'produk_id');
                                    @endphp

                                    @if ($isPending)
                                        <!-- Pending Analysis (Draft) - Click to Open Modal -->
                                        <button type="button"
                                            @click="openInputModal('{{ $dateStr }}', '{{ $dateReadable }}', {{ $existingItems }}, '{{ $dailySale->id }}')"
                                            class="absolute inset-0 z-10 w-full h-full cursor-pointer focus:outline-none"></button>

                                        <!-- Indicator for Pending -->
                                        <div class="absolute top-1 right-1">
                                            <div class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"
                                                title="Menunggu Analisis"></div>
                                        </div>
                                    @else
                                        <!-- Analyzed - Click to Show -->
                                        <a href="{{ route('daily-checkin.show', $dailySale->id) }}"
                                            class="absolute inset-0 z-10"></a>

                                        <div class="absolute top-1 right-1">
                                            <div class="w-2 h-2 rounded-full bg-green-500" title="Selesai"></div>
                                        </div>
                                    @endif
                                @elseif ($isCurrentMonth && $currentDate <= now())
                                    <button type="button"
                                        @click="openInputModal('{{ $dateStr }}', '{{ $dateReadable }}')"
                                        class="absolute inset-0 z-10 w-full h-full cursor-pointer focus:outline-none"></button>
                                @endif

                                <div class="flex justify-between items-start mb-1 pointer-events-none">
                                    <span
                                        class="text-xs font-medium w-6 h-6 flex items-center justify-center rounded-full
                                        {{ $isToday ? 'bg-indigo-600 text-white shadow-sm' : ($isCurrentMonth ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400 dark:text-gray-600') }}">
                                        {{ $currentDate->day }}
                                    </span>
                                </div>

                                <div class="mt-auto pl-1 pointer-events-none">
                                    @if ($dailySale)
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-gray-400 uppercase">Omset</span>
                                            <span
                                                class="text-sm font-bold text-gray-900 dark:text-white tabular-nums truncate">
                                                {{ number_format($dailySale->total_revenue / 1000, 0, ',', '.') }}k
                                            </span>
                                        </div>
                                    @elseif ($isCurrentMonth && $currentDate <= now())
                                        <div class="hidden group-hover:flex items-center text-gray-400 text-xs">
                                            <span class="font-medium text-indigo-500">+ Input</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @php $currentDate->addDay(); @endphp
                        @endwhile
                    </div>
                </div>
            </div>

            <div class="w-full xl:w-80 shrink-0 order-1 xl:order-2 space-y-4">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                    Ringkasan {{ $startOfMonth->translatedFormat('F') }}
                </h3>
                @foreach ($cards as $card)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700 {{ $card['border'] }} relative overflow-hidden">
                        <div class="flex justify-between items-start relative z-10">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ $card['title'] }}</p>
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white mt-1 tabular-nums">
                                    {{ $card['value'] }}
                                </h4>
                                <p
                                    class="text-xs mt-2 {{ $card['text_color'] }} font-medium bg-gray-50 dark:bg-gray-900/50 inline-block px-2 py-1 rounded-md">
                                    {{ $card['desc'] }}
                                </p>
                            </div>
                        </div>
                        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-20 {{ $card['bg_icon'] }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div x-show="createModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="createModalOpen" x-transition.opacity
                    class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="closeModal()"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="createModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative z-10 inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">

                    <form :action="formAction" method="POST" @submit="isLoading = true">
                        @csrf
                        <input type="hidden" name="_method" :value="method">
                        <input type="hidden" name="date" x-model="targetDate">

                        <div
                            class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Input Penjualan</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" x-text="targetDateDisplay"></p>
                            </div>
                            <button type="button" @click="closeModal()"
                                class="text-gray-400 hover:text-gray-500 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 p-1">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse ($produks as $produk)
                                    <div
                                        class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-500 transition-all duration-300 p-3 flex gap-3 items-center">

                                        <div
                                            class="w-16 h-16 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden relative">
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

                                        <div class="flex-1 min-w-0 flex flex-col justify-between h-16">
                                            <div>
                                                <h3 class="font-bold text-gray-900 dark:text-white text-sm leading-tight truncate"
                                                    title="{{ $produk->nama_produk }}">
                                                    {{ $produk->nama_produk }}
                                                </h3>
                                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">
                                                    Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                                </p>
                                            </div>

                                            <div class="flex items-center justify-end">
                                                <div
                                                    class="flex items-center bg-gray-50 dark:bg-gray-700/50 rounded-lg p-0.5 border border-gray-200 dark:border-gray-600">
                                                    <button type="button" @click="if(salesData[{{ $produk->id }}] > 0) salesData[{{ $produk->id }}]--"
                                                        class="w-6 h-6 flex items-center justify-center rounded text-gray-500 hover:bg-white dark:hover:bg-gray-600 hover:text-indigo-600 transition-all disabled:opacity-50">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M20 12H4" />
                                                        </svg>
                                                    </button>

                                                    <input type="number" name="sales[{{ $produk->id }}]"
                                                        x-model="salesData[{{ $produk->id }}]" min="0"
                                                        class="w-8 text-center bg-transparent border-none p-0 text-sm font-bold text-gray-800 dark:text-white focus:ring-0 appearance-none [-moz-appearance:_textfield] [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none">

                                                    <button type="button" @click="salesData[{{ $produk->id }}]++"
                                                        class="w-6 h-6 flex items-center justify-center rounded text-gray-500 hover:bg-white dark:hover:bg-gray-600 hover:text-indigo-600 transition-all">
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
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada produk. Tambahkan di
                                            Katalog dulu.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3 border-t border-gray-100 dark:border-gray-700">
                            <button type="button" @click="closeModal()"
                                class="px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg border border-gray-300 dark:border-gray-600 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 shadow-sm shadow-indigo-500/30 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan & Analisa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
