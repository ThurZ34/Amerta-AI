@extends('layouts.app')

@section('header', 'Amerta AI Assistant')

@section('content')
    {{-- Main Container dengan tinggi yang pas --}}
    <div
        class="relative min-h-[calc(100vh-4rem)] flex flex-col justify-center py-6 sm:px-6 lg:px-8 bg-gray-50/50 dark:bg-gray-950 overflow-hidden">

        {{-- Background Decor (Orbs - Hiasan Latar Belakang) --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-full pointer-events-none z-0">
            <div
                class="absolute top-20 left-10 w-72 h-72 bg-indigo-500/20 rounded-full blur-[100px] mix-blend-multiply dark:mix-blend-screen animate-pulse">
            </div>
            <div
                class="absolute bottom-20 right-10 w-72 h-72 bg-purple-500/20 rounded-full blur-[100px] mix-blend-multiply dark:mix-blend-screen animation-delay-2000 animate-pulse">
            </div>
        </div>

        {{-- Chatbot Card Container --}}
        <div
            class="relative z-10 w-full max-w-5xl mx-auto h-[85vh] flex flex-col bg-white dark:bg-gray-900 rounded-3xl shadow-2xl shadow-indigo-500/10 border border-gray-100 dark:border-gray-800 overflow-hidden backdrop-blur-xl">

            {{-- 1. Chat Header (FIXED SECTION) --}}
            <div
                class="shrink-0 h-20 px-6 flex items-center justify-between border-b border-gray-100 dark:border-gray-800 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md z-20">

                {{-- Brand --}}
                <div class="flex items-center gap-4">
                    {{-- Icon Container (Diganti jadi Solid bg-indigo-600 agar aman) --}}
                    <div
                        class="relative w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        {{-- Online Dot Indicator --}}
                        <span
                            class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-gray-900 rounded-full">
                            <span
                                class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75 animate-ping"></span>
                        </span>
                    </div>
                    <div>
                        {{-- Text Title (Diganti jadi text-black agar kontras di light mode) --}}
                        <h1 class="text-lg font-extrabold text-black dark:text-white leading-tight">
                            Amerta<span class="text-indigo-600 dark:text-indigo-400">.AI</span>
                        </h1>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800">
                                PRO MODEL
                            </span>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Selalu Aktif</span>
                        </div>
                    </div>
                </div>

                {{-- Header Actions (Optional - Hidden on small screens) --}}
                <div class="hidden sm:flex items-center gap-3">
                    <div class="text-right hidden md:block mr-2">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Butuh bantuan strategi?</p>
                        <p class="text-xs text-indigo-700 dark:text-indigo-400 font-bold">Tanyakan apa saja pada AI.</p>
                    </div>
                </div>
            </div>

            {{-- 2. Chat Component Area --}}
            <div class="flex-1 overflow-hidden relative bg-gray-50/50 dark:bg-gray-950/50">
                {{-- Background Pattern Grid (Optional subtle effect) --}}
                <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none"
                    style="background-image: radial-gradient(#6366f1 1px, transparent 1px); background-size: 24px 24px;">
                </div>

                {{-- Livewire Component Wrapper --}}
                <div class="h-full w-full relative z-10">
                    @livewire('dashboard-chat', ['mode' => 'full'])
                </div>
            </div>

        </div>

        {{-- Footer Credit --}}
        <div class="text-center mt-4 relative z-10">
            <p class="text-xs text-gray-500 dark:text-gray-500 font-medium">
                Amerta AI mungkin membuat kesalahan. Selalu verifikasi informasi penting.
            </p>
        </div>

    </div>
@endsection
