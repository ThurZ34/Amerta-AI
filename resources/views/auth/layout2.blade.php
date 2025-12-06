<!DOCTYPE html>
<html lang="en">

<head  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="scroll-smooth"
      x-data="{
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
    }">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    {{-- Tailwind CSS (pakai Laravel Breeze atau CDN opsional) --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        {{-- ================================
            LEFT SIDE (FORM)
        ================================= --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center px-8 py-10">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>


        {{-- ================================
            RIGHT SIDE (GRADIENT + ILUSTRASI)
            Mengambil gambar dari: @yield('image_url')
        ================================= --}}
        <div class="hidden lg:flex w-1/2 relative overflow-hidden items-center justify-center">

            {{-- Background gradient ungu --}}
            <div class="absolute inset-0 bg-[linear-gradient(160deg,#7b3efc,#6b2af3,#4b1fd0)]"></div>

            {{-- Lingkaran glow putih --}}
            <div class="absolute -right-24 top-1/2 -translate-y-1/2 
                        w-[460px] h-[460px] bg-white/25 rounded-full blur-3xl">
            </div>

            {{-- Gambar ilustrasi dari halaman login/register --}}
            <img src="@yield('image_url')"
                 class="relative z-10 w-[380px] max-w-[90%] drop-shadow-2xl">

        </div>

    </div>

</body>
</html>
