@php
    $navLinks = [
        ['label' => 'About', 'url' => '#about'],
        ['label' => 'Solutions', 'url' => '#problem-solution'],
        ['label' => 'Features', 'url' => '#features'],
        ['label' => 'Pricing', 'url' => '#pricing'],
    ];

    $features = [
        ['title' => 'Lightning Fast', 'desc' => 'Optimized for speed and performance, ensuring your users have the best experience.', 'color' => 'indigo', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
        ['title' => 'Secure by Design', 'desc' => 'Built with security in mind. Your data is protected with enterprise-grade encryption.', 'color' => 'purple', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
        ['title' => 'Easy to Use', 'desc' => 'Intuitive interface that requires no training. Get up and running in minutes.', 'color' => 'pink', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['title' => 'Analytics', 'desc' => 'Gain deep insights into your performance with our advanced analytics dashboard.', 'color' => 'blue', 'icon' => 'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'],
        ['title' => 'Team Collaboration', 'desc' => 'Share, comment, and collaborate in real-time to get work done faster.', 'color' => 'orange', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
        ['title' => '24/7 Support', 'desc' => 'Our dedicated support team is always available to help you resolve any issues.', 'color' => 'teal', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z'],
    ];

    $plans = [
        ['name' => 'Starter', 'price' => '1.000.000', 'desc' => 'Perfect for individuals.', 'features' => ['5 Projects', 'Basic Analytics', 'Email Support'], 'popular' => false],
        ['name' => 'Professional', 'price' => '2.000.000', 'desc' => 'For growing teams.', 'features' => ['Unlimited Projects', 'Advanced Analytics', 'Priority Support', 'Team Collaboration'], 'popular' => true],
        ['name' => 'Enterprise', 'price' => 'Custom', 'desc' => 'Custom solutions.', 'features' => ['Dedicated Infrastructure', 'Custom Integrations', '24/7 Dedicated Support'], 'popular' => false],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth"
    x-data="{
        darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.darkMode);
        }
    }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Amerta') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .fade-in-section, .fade-in-left, .fade-in-right { opacity: 0; transition: all 0.8s ease-out; will-change: opacity, transform; }
        .fade-in-section { transform: translateY(30px); }
        .fade-in-left { transform: translateX(-50px); }
        .fade-in-right { transform: translateX(50px); }
        .is-visible { opacity: 1 !important; transform: none !important; }
        @keyframes blob { 0%, 100% { transform: translate(0, 0) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
    <script>if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.classList.add('dark');</script>
</head>

<body class="antialiased bg-gray-50 dark:bg-gray-950 text-slate-900 dark:text-gray-100 transition-colors duration-300">

    <nav class="fixed w-full z-50 transition-all duration-300 backdrop-blur-md border-b border-gray-200 dark:border-gray-800"
         x-data="{ scrolled: false, mobileOpen: false, langOpen: false, profileOpen: false }"
         :class="scrolled ? 'bg-white/90 dark:bg-gray-900/90 shadow-sm' : 'bg-white/80 dark:bg-gray-900/80'"
         @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="#" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-lg">A</span> Amerta
                </a>

                <div class="hidden md:flex space-x-8 items-center">
                    @foreach($navLinks as $link)
                        <a href="{{ $link['url'] }}" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium text-sm transition-colors">{{ __($link['label']) }}</a>
                    @endforeach

                    <div class="relative">
                        <button @click="langOpen = !langOpen" @click.away="langOpen = false" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m10.5 21 5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 0 1 6-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 0 1-3.827-5.802" /></svg>
                        </button>
                        <div x-show="langOpen" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                            @foreach(['en' => 'English', 'id' => 'Bahasa Indonesia'] as $code => $name)
                                <a href="{{ route('lang.switch', $code) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() == $code ? 'font-bold bg-gray-50 dark:bg-gray-700' : '' }}">{{ $name }}</a>
                            @endforeach
                        </div>
                    </div>

                    <button @click="toggleTheme()" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    @auth
                        <div class="relative ml-3">
                            <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex text-sm rounded-full focus:ring-2 focus:ring-indigo-500">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                            </button>
                            <div x-show="profileOpen" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5" style="display: none;">
                                <a href="{{ url('/main_menu') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100">{{ __('Main Menu') }}</a>
                                <form method="POST" action="{{ route('logout') }}"><button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100">{{ __('Sign out') }}</button> @csrf</form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 font-medium text-sm">{{ __('Log in') }}</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-5 py-2 rounded-full font-medium hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30 text-sm">{{ __('Get Started') }}</a>
                    @endauth
                </div>

                <div class="md:hidden flex items-center gap-4">
                     <button @click="toggleTheme()" class="p-2 text-gray-500"><svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg><svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg></button>
                     <button @click="mobileOpen = !mobileOpen" class="text-gray-600 dark:text-gray-300"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg></button>
                </div>
            </div>
        </div>
        </nav>

    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 relative z-10 text-center fade-in-section">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-sm font-medium mb-8 border border-indigo-100 dark:border-indigo-800">
                <span class="flex h-2 w-2 rounded-full bg-indigo-600 dark:bg-indigo-400 mr-2"></span> {{ __('New Feature: AI-Powered Analytics') }}
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 dark:text-white mb-8 leading-tight">
                {{ __('Build your dream') }} <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">{{ __('with Amerta') }}</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-600 dark:text-gray-400 mb-10">{{ __('A powerful platform designed to help you achieve your goals faster and more efficiently.') }}</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 shadow-xl flex items-center justify-center gap-2">{{ __('Start Free Trial') }} <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg></a>
                <a href="#about" class="px-8 py-4 bg-white dark:bg-gray-800 text-slate-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 rounded-full font-semibold hover:bg-gray-50 flex items-center justify-center gap-2">{{ __('Learn More') }}</a>
            </div>
            <div class="mt-16 relative mx-auto max-w-5xl rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 overflow-hidden bg-white dark:bg-gray-800 aspect-video flex items-center justify-center group fade-in-section">
                <img src="{{ asset('images/dashboard.png') }}" class="max-h-full max-w-full object-contain dark:hidden" alt="Light Dash" />
                <img src="{{ asset('images/dashboard_dark.png') }}" class="max-h-full max-w-full object-contain hidden dark:block" alt="Dark Dash" />
            </div>
        </div>
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 pointer-events-none">
            <div id="blob-1" class="absolute top-20 left-10 w-96 h-96 bg-purple-200 dark:bg-purple-900/20 rounded-full mix-blend-multiply blur-3xl opacity-30 animate-blob"></div>
            <div id="blob-2" class="absolute top-20 right-10 w-96 h-96 bg-indigo-200 dark:bg-indigo-900/20 rounded-full mix-blend-multiply blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div id="blob-3    $navLinks = [
" class="absolute -bottom-8 left-1/2 w-96 h-96 bg-pink-200 dark:bg-pink-900/20 rounded-full mix-blend-multiply blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>
    </div>

    <div id="about" class="py-24 bg-white dark:bg-gray-950 relative">
        <div class="max-w-7xl mx-auto px-4 lg:grid lg:grid-cols-2 lg:gap-16 items-center">
            <div class="mb-12 lg:mb-0 relative fade-in-left">
                <div class="absolute -top-4 -left-4 w-24 h-24 bg-indigo-100 dark:bg-indigo-900/30 rounded-full -z-10"></div>
                <div class="relative rounded-2xl overflow-hidden shadow-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 aspect-square flex items-center justify-center">
                    <p class="text-gray-400">About Us Image</p>
                </div>
            </div>
            <div class="fade-in-right">
                <h2 class="text-base text-indigo-600 font-semibold uppercase mb-2">{{ __('About Amerta') }}</h2>
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-6">{{ __('Empowering businesses to reach new heights') }}</h3>
                <p class="text-lg text-slate-600 dark:text-gray-400 mb-6">{{ __('Founded in 2025, Amerta was born from a simple idea: that powerful technology should be accessible to everyone.') }}</p>
                <div class="grid grid-cols-2 gap-6">
                    <div class="border-l-4 border-indigo-500 pl-4"><p class="text-3xl font-bold text-slate-900 dark:text-white">0</p><p class="text-sm text-slate-500">{{ __('Happy Clients') }}</p></div>
                    <div class="border-l-4 border-purple-500 pl-4"><p class="text-3xl font-bold text-slate-900 dark:text-white">99.9%</p><p class="text-sm text-slate-500">{{ __('Uptime Guarantee') }}</p></div>
                </div>
            </div>
        </div>
    </div>

    <div id="problem-solution" class="py-24 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 space-y-12">
            <div class="text-center fade-in-section">
                <h2 class="text-base text-indigo-600 font-semibold uppercase">{{ __('Why Choose Us') }}</h2>
                <p class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white">{{ __('Solving real-world challenges') }}</p>
            </div>
            @foreach([
                ['p_icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'p_text' => 'Managing multiple disconnected tools leads to data silos and lost productivity.', 's_icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 's_text' => 'Amerta provides a unified platform. Centralize your data and streamline workflows.'],
                ['p_icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'p_text' => 'Slow, clunky interfaces frustrate users and increase churn.', 's_icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 's_text' => 'We prioritize User Experience (UX). Our lightning-fast interface ensures high adoption.']
            ] as $item)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden lg:grid lg:grid-cols-2 {{ $loop->even ? 'fade-in-right' : 'fade-in-left' }}">
                <div class="p-8 lg:p-12 bg-red-50/50 dark:bg-red-900/10">
                    <div class="flex items-center gap-3 mb-4 text-red-600 dark:text-red-400"><div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['p_icon'] }}"></path></svg></div><h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('The Problem') }}</h3></div>
                    <p class="text-gray-600 dark:text-gray-300">{{ __($item['p_text']) }}</p>
                </div>
                <div class="p-8 lg:p-12 bg-green-50/50 dark:bg-green-900/10 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4 text-green-600 dark:text-green-400"><div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['s_icon'] }}"></path></svg></div><h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('The Solution') }}</h3></div>
                    <p class="text-gray-600 dark:text-gray-300">{{ __($item['s_text']) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div id="features" class="py-24 bg-white dark:bg-gray-950">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16 fade-in-section">
                <h2 class="text-base text-indigo-600 font-semibold uppercase">{{ __('Features') }}</h2>
                <p class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white">{{ __('Everything you need') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($features as $f)
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 hover:-translate-y-1 fade-in-section">
                    <div class="w-14 h-14 bg-{{ $f['color'] }}-100 dark:bg-{{ $f['color'] }}-900/30 rounded-2xl flex items-center justify-center mb-6 text-{{ $f['color'] }}-600 group-hover:bg-{{ $f['color'] }}-600 group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">{{ __($f['title']) }}</h3>
                    <p class="text-slate-600 dark:text-gray-400">{{ __($f['desc']) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="pricing" class="py-24 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16 fade-in-section">
                <h2 class="text-base text-indigo-600 font-semibold uppercase">{{ __('Pricing') }}</h2>
                <p class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white">{{ __('Simple pricing') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                <div class="{{ $plan['popular'] ? 'bg-indigo-50 dark:bg-indigo-900/30 border-2 border-indigo-500 transform md:-translate-y-4 shadow-2xl z-10' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700' }} rounded-2xl p-8 relative fade-in-section">
                    @if($plan['popular']) <div class="absolute top-0 right-0 bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-lg uppercase">Popular</div> @endif
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">{{ __($plan['name']) }}</h3>
                    <p class="text-slate-500 dark:text-gray-400 mb-6">{{ __($plan['desc']) }}</p>
                    <div class="flex items-baseline mb-8">
                        <span class="text-4xl font-extrabold text-slate-900 dark:text-white">{{ $plan['name'] === 'Enterprise' ? $plan['price'] : 'Rp. ' . $plan['price'] }}</span>
                        @if($plan['name'] !== 'Enterprise') <span class="text-slate-500 ml-2">/mo</span> @endif
                    </div>
                    <ul class="space-y-4 mb-8 text-slate-600 dark:text-gray-300">
                        @foreach($plan['features'] as $feat)
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> {{ __($feat) }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full py-3 px-4 {{ $plan['popular'] ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-100 dark:bg-gray-700 text-slate-900 dark:text-white hover:bg-gray-200' }} font-medium rounded-lg text-center transition-colors">{{ __('Get Started') }}</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-indigo-600 py-20 text-center relative overflow-hidden fade-in-section">
        <h2 class="text-3xl font-extrabold text-white mb-6 relative z-10">{{ __('Ready to transform?') }}</h2>
        <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-full font-bold shadow-xl relative z-10">{{ __('Create Free Account') }}</a>
    </div>

    <footer class="bg-gray-900 text-white py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center">
            <span class="text-2xl font-bold flex items-center gap-2 mb-4 md:mb-0"><span class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">A</span> Amerta</span>
            <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Amerta. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } });
            }, { threshold: 0.1 });
            document.querySelectorAll('.fade-in-section, .fade-in-left, .fade-in-right').forEach(s => observer.observe(s));
            
            // Parallax Blobs
            window.addEventListener('scroll', () => {
                const s = window.pageYOffset;
                ['blob-1','blob-2','blob-3'].forEach((id, i) => {
                    const el = document.getElementById(id);
                    if(el) el.style.transform = `translate(0px, ${s * (0.1 * (i+1))}px)`;
                });
            });
        });
    </script>
</body>
</html>