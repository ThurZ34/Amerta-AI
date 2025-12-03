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

    <title>Amerta AI Assistant</title>

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
            darkMode: 'class',
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
    class="h-full antialiased text-gray-900 bg-gray-50 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">

    {{-- Fullscreen Container --}}
    <div class="relative h-screen bg-gray-50 dark:bg-gray-950 overflow-hidden flex flex-col">

        {{-- Background Decor (Orbs) --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full pointer-events-none z-0">
            <div
                class="absolute top-20 left-10 w-96 h-96 bg-indigo-500/10 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-screen animate-pulse">
            </div>
            <div
                class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/10 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-screen animation-delay-2000 animate-pulse">
            </div>
        </div>

        {{-- Background Pattern Grid --}}
        <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none z-0"
            style="background-image: radial-gradient(#6366f1 1px, transparent 1px); background-size: 24px 24px;">
        </div>

        {{-- Back to Main Menu Button (Floating) --}}
        <a href="{{ route('main_menu') }}"
            class="absolute top-4 right-4 z-50 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-4 py-2 rounded-xl shadow-sm hover:shadow-md transition-all flex items-center gap-2 text-sm font-medium group">
            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Menu
        </a>

        {{-- Livewire Component Wrapper --}}
        <div class="flex-1 relative z-10">
            @livewire('dashboard-chat', ['mode' => 'full'])
        </div>

    </div>

    @livewireScripts
</body>

</html>
