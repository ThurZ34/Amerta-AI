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
    x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'));
    if (darkMode) document.documentElement.classList.add('dark');" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body {
            font-family: 'Inter', sans-serif;
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

        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0 h-full">
            @include('layouts.partials.header')

            <main
                class="flex-1 h-full overflow-hidden bg-gray-50 dark:bg-gray-950 relative flex flex-col transition-colors duration-300">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Floating Chat Logic Wrapper -->
    <div x-data="{
        chatOpen: false,
        isFullscreen: false,
        isDragging: false,
        pos: { x: 0, y: 0 },
        start: { x: 0, y: 0 },

        // Fungsi Mulai Geser (Drag)
        startDrag(e) {
            if (this.isFullscreen) return; // Jangan geser kalau lagi fullscreen
            this.isDragging = true;
            this.start.x = e.clientX - this.pos.x;
            this.start.y = e.clientY - this.pos.y;
        },
        // Fungsi Sedang Menggeser
        doDrag(e) {
            if (this.isDragging) {
                this.pos.x = e.clientX - this.start.x;
                this.pos.y = e.clientY - this.start.y;
            }
        },
        // Fungsi Stop Geser
        stopDrag() {
            this.isDragging = false;
        },
        // Reset Posisi saat Fullscreen dimatikan
        toggleFullscreen() {
            this.isFullscreen = !this.isFullscreen;
            if (this.isFullscreen) {
                // Reset posisi saat fullscreen agar pas di layar
                this.pos = { x: 0, y: 0 };
            }
        }
    }" @mousemove.window="doDrag($event)" @mouseup.window="stopDrag()" class="fixed z-50"
        :class="isFullscreen ? 'inset-0' : 'bottom-6 right-6'">

        <!-- CHAT MODAL WINDOW -->
        <div x-show="chatOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-10 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-10 scale-95" {{-- LOGIC STYLE UNTUK GESER & FULLSCREEN --}}
            :class="isFullscreen
                ?
                'w-full h-full rounded-none' :
                'w-[350px] md:w-[400px] h-[500px] md:h-[600px] rounded-2xl mb-16'"
            :style="!isFullscreen ?
                `transform: translate(${pos.x}px, ${pos.y}px); resize: both; overflow: hidden; min-width: 300px; min-height: 400px;` :
                ''"
            class="bg-white dark:bg-gray-900 shadow-2xl border border-gray-200 dark:border-gray-800 flex flex-col transition-all duration-75 ease-out relative"
            style="display: none;">

            <!-- HEADER MODAL (DRAGGABLE AREA) -->
            <!-- Tambahkan cursor-move agar user tau ini bisa digeser -->
            <div @mousedown="startDrag($event)"
                class="h-14 bg-indigo-600 flex items-center justify-between px-4 shrink-0 cursor-move select-none"
                :class="isFullscreen ? '' : 'rounded-t-2xl'">

                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-xs pointer-events-none">
                        AI
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-sm pointer-events-none">Amerta Assistant</h3>
                        <p class="text-indigo-200 text-[10px] pointer-events-none">Online</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">

                    <button @click.stop="toggleFullscreen()"
                        class="text-white/70 hover:text-white transition p-1 hover:bg-white/10 rounded">
                        <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                            </path>
                        </svg>

                        <svg x-show="isFullscreen" class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </button>

                    <button @click="chatOpen = false; isFullscreen = false; pos = {x:0, y:0}"
                        class="text-white/70 hover:text-white transition p-1 hover:bg-red-500/80 rounded">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Chat Content -->
            <div class="flex-1 relative overflow-hidden flex flex-col">
                @livewire('dashboard-chat')
            </div>

            <!-- Resize Handle (Visual Only - Native CSS resize used) -->
            <div x-show="!isFullscreen"
                class="absolute bottom-0 right-0 w-4 h-4 cursor-se-resize pointer-events-none opacity-50">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-4 h-4 text-gray-400">
                    <path d="M19 5v14H5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>

        <!-- TOGGLE BUTTON (BUBBLE) -->
        <!-- Tombol ini akan sembunyi jika Fullscreen aktif -->
        <button @click="chatOpen = !chatOpen" x-show="!isFullscreen"
            class="absolute bottom-0 right-0 w-14 h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-110 active:scale-95 group z-50">
            <span x-show="!chatOpen" class="text-xl font-bold">AI</span>
            <svg x-show="chatOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>

            <!-- Notification Badge -->
            <span
                class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-white dark:border-gray-900"></span>
        </button>
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
