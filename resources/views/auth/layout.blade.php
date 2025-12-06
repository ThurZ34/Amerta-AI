<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="scroll-smooth"
      x-data="{
        darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage)
                  && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');

            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Amerta AI</title>

    {{-- Dark mode check --}}
    <script>
        if (localStorage.getItem('theme') === 'dark'
            || (!('theme' in localStorage)
            && window.matchMedia('(prefers-color-scheme: dark)').matches)) {

            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine JS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Tailwind Config --}}
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
    </script>

    {{-- Font --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-white dark:bg-gray-950 transition-colors duration-300">

    <div class="flex min-h-screen">

        {{-- =====================================================
            LEFT SIDE (FORM LOGIN / REGISTER)
        ====================================================== --}}
        <div class="w-full md:w-1/2 flex items-center justify-center p-8
                    bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100
                    relative transition-colors duration-300">

            {{-- Toggle Theme --}}
            <button @click="toggleTheme()"
                class="absolute top-6 right-6 p-2 rounded-full text-gray-500 hover:bg-gray-100
                       dark:text-gray-400 dark:hover:bg-gray-800 focus:outline-none transition-colors">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>

                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>

            <div class="w-full max-w-md space-y-6">
                @yield('content')
            </div>
        </div>



        {{-- =====================================================
            RIGHT SIDE (SATU FOTO PENUH)
            Gambar diambil dari: @yield('image_url')
        ====================================================== --}}
        <div class="hidden md:block w-1/2 relative">
            <img src="@yield('image_url')"
                 alt="Auth Banner"
                 class="w-full h-full object-cover">
        </div>

    </div>
</body>
</html>
