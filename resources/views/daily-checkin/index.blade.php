@extends('layouts.app')

@section('content')
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
                'title' => 'Total Profit',
                'value' => '+Rp ' . number_format($monthlyProfit, 0, ',', '.'),
                'desc' => 'Margin: ' . ($monthlyRevenue > 0 ? round(($monthlyProfit / $monthlyRevenue) * 100, 1) : 0) . '%',
                'bg_icon' => 'bg-green-50 dark:bg-green-900/20',
                'text_color' => 'text-green-600 dark:text-green-400',
                'border' => 'border-l-4 border-green-500',
            ],
            [
                'title' => 'Rekor Harian',
                'value' => $bestDay ? 'Rp ' . number_format($bestDay->total_revenue, 0, ',', '.') : '-',
                'desc' => $bestDay ? \Carbon\Carbon::parse($bestDay->date)->translatedFormat('d M Y') : 'Belum ada data',
                'bg_icon' => 'bg-purple-50 dark:bg-purple-900/20',
                'text_color' => 'text-purple-600 dark:text-purple-400',
                'border' => 'border-l-4 border-purple-500',
            ],
        ];
    @endphp

    <div class="p-4 sm:p-6 lg:p-8 h-full">
        
        <div class="flex flex-col xl:flex-row gap-6 h-full">
            
            <div class="flex-1 order-2 xl:order-1 flex flex-col h-full">
                
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Kalender Penjualan
                    </h2>
                    
                    <div class="flex items-center bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-1">
                        <a href="{{ route('daily-checkin.index', ['date' => $startOfMonth->copy()->subMonth()->format('Y-m-d')]) }}" 
                           class="p-1.5 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md text-gray-500 dark:text-gray-400 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                        <span class="px-4 font-semibold text-gray-900 dark:text-white text-sm min-w-[140px] text-center">
                            {{ $startOfMonth->translatedFormat('F Y') }}
                        </span>
                        <a href="{{ route('daily-checkin.index', ['date' => $startOfMonth->copy()->addMonth()->format('Y-m-d')]) }}" 
                           class="p-1.5 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md text-gray-500 dark:text-gray-400 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex-1 flex flex-col">
                    <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                        @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                            <div class="py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
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
                                $dailySale = $dailySales[$dateStr] ?? null;
                                $isToday = $currentDate->isToday();
                            @endphp

                            <div class="min-h-[100px] bg-white dark:bg-gray-800 p-2 relative group flex flex-col transition-colors
                                {{ !$isCurrentMonth ? 'bg-gray-50/50 dark:bg-gray-900/50' : 'hover:bg-gray-50 dark:hover:bg-gray-750' }}">

                                {{-- Link Full Card --}}
                                @if ($dailySale)
                                    <a href="{{ route('daily-checkin.show', $dailySale->id) }}" class="absolute inset-0 z-10"></a>
                                @elseif ($isCurrentMonth && $currentDate <= now())
                                    <a href="{{ route('daily-checkin.create', ['date' => $dateStr]) }}" class="absolute inset-0 z-10"></a>
                                @endif

                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-xs font-medium w-6 h-6 flex items-center justify-center rounded-full 
                                        {{ $isToday ? 'bg-indigo-600 text-white shadow-sm' : ($isCurrentMonth ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400 dark:text-gray-600') }}">
                                        {{ $currentDate->day }}
                                    </span>
                                    
                                    @if ($dailySale)
                                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    @endif
                                </div>

                                <div class="mt-auto pl-1">
                                    @if ($dailySale)
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-gray-400 uppercase">Omset</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white tabular-nums truncate">
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
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700 {{ $card['border'] }} relative overflow-hidden">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ $card['title'] }}</p>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mt-1 tabular-nums">
                                {{ $card['value'] }}
                            </h4>
                            <p class="text-xs mt-2 {{ $card['text_color'] }} font-medium bg-gray-50 dark:bg-gray-900/50 inline-block px-2 py-1 rounded-md">
                                {{ $card['desc'] }}
                            </p>
                        </div>
                    </div>
                    <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-20 {{ $card['bg_icon'] }}"></div>
                </div>
                @endforeach

                <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-xl p-4 border border-indigo-100 dark:border-indigo-800/30">
                    <div class="flex gap-3">
                        <div class="mt-0.5">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-indigo-900 dark:text-indigo-100">Tips Cepat</h4>
                            <p class="text-xs text-indigo-700 dark:text-indigo-300 mt-1 leading-relaxed">
                                Klik tanggal kosong untuk input data penjualan. Klik tanggal yang sudah terisi untuk melihat detail atau mengedit.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection