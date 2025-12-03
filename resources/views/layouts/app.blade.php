<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class', // Pastikan ini 'class'
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
        marked.use({
            breaks: true,
            gfm: true
        });
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body
    class="h-full antialiased text-gray-900 bg-gray-50 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300"
    x-data="{ sidebarOpen: false, chatOpen: false }">
    <div class="h-screen flex flex-col md:flex-row overflow-hidden">

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/80 z-40 md:hidden" style="display: none;"></div>

        @unless (request()->routeIs('main_menu') ||
                request()->routeIs('amerta') ||
                request()->routeIs('dashboard-selection') ||
                request()->routeIs('dashboard-selection.join'))
            @include('layouts.partials.sidebar')
        @endunless

        <div class="flex-1 flex flex-col min-w-0 h-full">
            @unless (request()->routeIs('dashboard-selection') || request()->routeIs('dashboard-selection.join'))
                @include('layouts.partials.header')
            @endunless

            <main
                class="flex-1 h-full overflow-y-auto bg-gray-50 dark:bg-gray-950 relative flex flex-col transition-colors duration-300">
                @yield('content')
            </main>
        </div>
    </div>

    <div x-data="{
        chatOpen: false,
        isFullscreen: false,
        isDragging: false,
        pos: { x: 0, y: 0 },
        start: { x: 0, y: 0 },
        limits: { minX: 0, maxX: 0, minY: 0, maxY: 0 },
    
        startDrag(e) {
            if (this.isFullscreen) return;
            this.isDragging = true;
            this.start.x = e.clientX - this.pos.x;
            this.start.y = e.clientY - this.pos.y;
    
            const winW = window.innerWidth;
            const winH = window.innerHeight;
            const rect = this.$refs.chatModal.getBoundingClientRect();
    
            // Margin aman
            const baseRight = 24;
            const baseBottom = 96;
    
            this.limits = {
                maxX: baseRight,
                minX: -(winW - rect.width - baseRight),
                maxY: baseBottom,
                minY: -(winH - rect.height - baseBottom)
            };
        },
    
        doDrag(e) {
            if (this.isDragging) {
                let rawX = e.clientX - this.start.x;
                let rawY = e.clientY - this.start.y;
                this.pos.x = Math.max(this.limits.minX, Math.min(rawX, this.limits.maxX));
                this.pos.y = Math.max(this.limits.minY, Math.min(rawY, this.limits.maxY));
            }
        },
    
        stopDrag() {
            this.isDragging = false;
        },
    
        toggleFullscreen() {
            this.isFullscreen = !this.isFullscreen;
            this.pos = { x: 0, y: 0 };
        }
    }" @mousemove.window="doDrag($event)" @mouseup.window="stopDrag()"
        class="fixed z-50 inset-0 pointer-events-none overflow-hidden">

        <div x-ref="chatModal" x-show="chatOpen"
            x-transition:enter="transition-all ease-[cubic-bezier(0.19,1,0.22,1)] duration-500"
            x-transition:enter-start="opacity-0 translate-y-10 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition-all ease-[cubic-bezier(0.19,1,0.22,1)] duration-300"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-10 scale-95"
            :class="isFullscreen
                ?
                'fixed right-0 bottom-0 w-full h-full rounded-none' :
                'fixed right-6 bottom-24 w-[350px] md:w-[400px] h-[500px] md:h-[600px] rounded-2xl shadow-2xl'"
            :style="!isFullscreen ? `transform: translate(${pos.x}px, ${pos.y}px);` : ''"
            class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 flex flex-col pointer-events-auto origin-bottom-right transition-all duration-500 ease-[cubic-bezier(0.19,1,0.22,1)] will-change-[width,height,border-radius,transform]"
            style="display: none;">

            <div @mousedown="startDrag($event)" @dblclick="toggleFullscreen()"
                class="h-14 bg-indigo-600 flex items-center justify-between px-4 shrink-0 select-none transition-all duration-300"
                :class="isFullscreen ? 'cursor-default' : 'cursor-move rounded-t-2xl'">

                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-xs pointer-events-none">
                        AI
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button @click.stop="toggleFullscreen()"
                        class="text-white/70 hover:text-white transition p-2 hover:bg-white/10 rounded-lg group">
                        <svg x-show="!isFullscreen" class="w-4 h-4 transform group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                            </path>
                        </svg>
                        <svg x-show="isFullscreen" class="w-4 h-4 transform group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 9V4.5M9 9H4.5M9 9 3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5 5.25 5.25">
                            </path>
                        </svg>
                    </button>

                    <button @click="chatOpen = false; isFullscreen = false; pos = {x:0, y:0}"
                        class="text-white/70 hover:text-white transition p-2 hover:bg-red-500/80 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 relative overflow-hidden flex flex-col bg-gray-50 dark:bg-gray-900">
                @livewire('dashboard-chat', ['mode' => 'quick'])
            </div>

            <div x-show="!isFullscreen"
                class="absolute bottom-0 right-0 w-6 h-6 pointer-events-none opacity-50 flex items-end justify-end p-1">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path d="M19 5v14H5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>

        @unless (request()->routeIs('main_menu') ||
                request()->routeIs('amerta') ||
                request()->routeIs('dashboard-selection') ||
                request()->routeIs('dashboard-selection.join'))
            <div class="absolute bottom-6 right-6 pointer-events-auto" x-show="!isFullscreen">
                <button @click="chatOpen = !chatOpen"
                    class="w-14 h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 group z-50 relative">

                    <span x-show="!chatOpen"
                        class="text-xl font-bold transition-transform duration-300 group-hover:rotate-12">AI</span>

                    <svg x-show="chatOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>

                    <span
                        class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-white dark:border-gray-900 animate-pulse"></span>
                </button>
            </div>
        @endunless

    </div>

    <style>
        .chat-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .chat-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        .dark .chat-scroll::-webkit-scrollbar-thumb {
            background-color: #4f46e5;
        }
    </style>
    @livewireScripts
</body>

</html>
