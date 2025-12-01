@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Daily Sales Calendar</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Track your daily sales performance.</p>
                </div>

                <div
                    class="flex items-center gap-4 bg-white dark:bg-gray-800 p-2 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <a href="{{ route('daily-checkin.index', ['date' => $startOfMonth->copy()->subMonth()->format('Y-m-d')]) }}"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </a>
                    <span class="font-bold text-gray-900 dark:text-white min-w-[140px] text-center">
                        {{ $startOfMonth->translatedFormat('F Y') }}
                    </span>
                    <a href="{{ route('daily-checkin.index', ['date' => $startOfMonth->copy()->addMonth()->format('Y-m-d')]) }}"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Days Header -->
                <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div
                            class="py-4 text-center text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 bg-gray-200 dark:bg-gray-700 gap-px">
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

                        <div
                            class="min-h-[120px] bg-white dark:bg-gray-800 p-2 relative group transition hover:bg-gray-50 dark:hover:bg-gray-750 flex flex-col {{ !$isCurrentMonth ? 'opacity-40 bg-gray-50/50 dark:bg-gray-900/50' : '' }}">

                            <div class="flex justify-between items-start">
                                <span
                                    class="text-sm font-medium {{ $isToday ? 'bg-indigo-600 text-white w-7 h-7 flex items-center justify-center rounded-full shadow-md' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $currentDate->day }}
                                </span>
                                @if ($dailySale)
                                    <span
                                        class="text-xs font-bold text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-1.5 py-0.5 rounded">
                                        Done
                                    </span>
                                @endif
                            </div>

                            <div class="mt-auto">
                                @if ($dailySale)
                                    <a href="{{ route('daily-checkin.show', $dailySale->id) }}"
                                        class="block w-full text-left">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Omset</div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                            Rp {{ number_format($dailySale->total_revenue, 0, ',', '.') }}
                                        </div>
                                        <div class="text-[10px] text-green-600 dark:text-green-400 truncate">
                                            +Rp {{ number_format($dailySale->total_profit, 0, ',', '.') }}
                                        </div>
                                    </a>
                                @else
                                    @if ($isCurrentMonth && $currentDate <= now())
                                        <a href="{{ route('daily-checkin.create', ['date' => $dateStr]) }}"
                                            class="flex flex-col items-center justify-center w-full h-full py-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition group-hover:scale-105">
                                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <span class="text-xs font-medium">Input</span>
                                        </a>
                                    @endif
                                @endif
                            </div>

                        </div>

                        @php $currentDate->addDay(); @endphp
                    @endwhile
                </div>
            </div>
        </div>
    </div>
@endsection
