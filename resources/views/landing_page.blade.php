<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" x-data="{
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
    if (darkMode) document.documentElement.classList.add('dark');">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Amerta') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom Scroll Animations */
        .fade-in-section {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
            will-change: opacity, transform;
        }

        .fade-in-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
            will-change: opacity, transform;
        }

        .fade-in-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
            will-change: opacity, transform;
        }

        .is-visible {
            opacity: 1 !important;
            transform: none !important;
        }

        /* Parallax Blob Animation */
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</head>

<body class="antialiased bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 transition-all duration-300"
        x-data="{ scrolled: false }"
        :class="{ 'bg-white/90 dark:bg-gray-900/90 shadow-sm': scrolled, 'bg-white/80 dark:bg-gray-900/80': !scrolled }"
        @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="#"
                        class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 tracking-tight flex items-center gap-2">
                        <span
                            class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">A</span>
                        Amerta
                    </a>
                </div>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#about"
                        class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium text-sm">About</a>
                    <a href="#problem-solution"
                        class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium text-sm">Solutions</a>
                    <a href="#features"
                        class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium text-sm">Features</a>
                    <a href="#pricing"
                        class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium text-sm">Pricing</a>

                    <!-- Theme Toggle Button -->
                    <button @click="toggleTheme()"
                        class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition-colors">
                        <!-- Sun Icon -->
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <!-- Moon Icon -->
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>

                    <a href="{{ route('login') }}"
                        class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium text-sm">Log
                        in</a>
                    <a href="{{ route('register') }}"
                        class="bg-indigo-600 text-white px-5 py-2 rounded-full font-medium hover:bg-indigo-700 dark:hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30 text-sm">Get
                        Started</a>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center gap-4">
                    <!-- Mobile Theme Toggle -->
                    <button @click="toggleTheme()"
                        class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition-colors">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>

                    <button
                        class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- 1. Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center fade-in-section">
            <div
                class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-sm font-medium mb-8 animate-fade-in-up border border-indigo-100 dark:border-indigo-800">
                <span class="flex h-2 w-2 rounded-full bg-indigo-600 dark:bg-indigo-400 mr-2"></span>
                New Feature: AI-Powered Analytics
            </div>
            <h1
                class="text-5xl md:text-7xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-8 leading-tight">
                Build your dream <br>
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">with
                    Amerta</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500 dark:text-gray-400 mb-10 leading-relaxed">
                A powerful platform designed to help you achieve your goals faster and more efficiently. Start your
                journey today with our comprehensive suite of tools.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="px-8 py-4 bg-indigo-600 text-white rounded-full font-semibold text-lg hover:bg-indigo-700 dark:hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-200 dark:shadow-indigo-900/30 transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    Start Free Trial
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="#about"
                    class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 rounded-full font-semibold text-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all hover:border-gray-300 dark:hover:border-gray-600 flex items-center justify-center gap-2">
                    Learn More
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </a>
            </div>

            <!-- Hero Image Placeholder -->
            <div
                class="mt-16 relative mx-auto max-w-5xl rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 overflow-hidden bg-gray-100 dark:bg-gray-800 aspect-video flex items-center justify-center group fade-in-section">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/10 to-transparent pointer-events-none">
                </div>
                <p
                    class="text-gray-400 dark:text-gray-500 font-medium text-lg group-hover:scale-105 transition-transform duration-300">
                    Dashboard Preview Image</p>
            </div>
        </div>

        <!-- Decorative Background Elements with Parallax -->
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 pointer-events-none">
            <div id="blob-1"
                class="absolute top-20 left-10 w-96 h-96 bg-purple-200 dark:bg-purple-900/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-30 animate-blob">
            </div>
            <div id="blob-2"
                class="absolute top-20 right-10 w-96 h-96 bg-indigo-200 dark:bg-indigo-900/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-30 animate-blob animation-delay-2000">
            </div>
            <div id="blob-3"
                class="absolute -bottom-8 left-1/2 w-96 h-96 bg-pink-200 dark:bg-pink-900/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-30 animate-blob animation-delay-4000">
            </div>
        </div>
    </div>

    <!-- 2. About Section -->
    <div id="about" class="py-24 bg-white dark:bg-gray-950 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
                <div class="mb-12 lg:mb-0 relative fade-in-left">
                    <div
                        class="absolute -top-4 -left-4 w-24 h-24 bg-indigo-100 dark:bg-indigo-900/30 rounded-full z-0">
                    </div>
                    <div
                        class="absolute -bottom-4 -right-4 w-32 h-32 bg-purple-100 dark:bg-purple-900/30 rounded-full z-0">
                    </div>
                    <div
                        class="relative z-10 rounded-2xl overflow-hidden shadow-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 aspect-square flex items-center justify-center">
                        <p class="text-gray-400 dark:text-gray-600 font-medium">About Us Image</p>
                    </div>
                </div>
                <div class="fade-in-right">
                    <h2
                        class="text-base text-indigo-600 dark:text-indigo-400 font-semibold tracking-wide uppercase mb-2">
                        About Amerta</h2>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl mb-6">
                        Empowering businesses to reach new heights
                    </h3>
                    <p class="text-lg text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                        Founded in 2024, Amerta was born from a simple idea: that powerful technology should be
                        accessible to everyone. We believe in democratizing digital transformation, making
                        enterprise-grade tools available to startups and growing businesses.
                    </p>
                    <p class="text-lg text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                        Our team of dedicated engineers and designers work tirelessly to create intuitive, robust, and
                        scalable solutions that grow with you. We are not just a service provider; we are your partner
                        in success.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="border-l-4 border-indigo-500 pl-4">
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">500+</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Happy Clients</p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-4">
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">99.9%</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Uptime Guarantee</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Problem And Solutions Section -->
    <div id="problem-solution" class="py-24 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in-section">
                <h2 class="text-base text-indigo-600 dark:text-indigo-400 font-semibold tracking-wide uppercase">Why
                    Choose Us</h2>
                <p
                    class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Solving real-world challenges
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 mx-auto">
                    We understand the hurdles you face. Here is how Amerta bridges the gap between problems and success.
                </p>
            </div>

            <div class="space-y-12">
                <!-- Problem/Solution 1 -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden lg:grid lg:grid-cols-2 transform hover:scale-[1.01] transition-transform duration-300 fade-in-left">
                    <div class="p-8 lg:p-12 flex flex-col justify-center bg-red-50/50 dark:bg-red-900/10">
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">The Problem</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">
                            Managing multiple disconnected tools leads to data silos, inefficiency, and lost
                            productivity. Teams struggle to stay aligned.
                        </p>
                    </div>
                    <div
                        class="p-8 lg:p-12 flex flex-col justify-center bg-green-50/50 dark:bg-green-900/10 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">The Solution</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">
                            Amerta provides a unified all-in-one platform. Centralize your data, streamline workflows,
                            and keep your entire team in sync effortlessly.
                        </p>
                    </div>
                </div>

                <!-- Problem/Solution 2 -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden lg:grid lg:grid-cols-2 transform hover:scale-[1.01] transition-transform duration-300 fade-in-right">
                    <div class="p-8 lg:p-12 flex flex-col justify-center bg-red-50/50 dark:bg-red-900/10">
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">The Problem</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">
                            Slow, clunky interfaces frustrate users and increase churn. Complexity becomes a barrier to
                            adoption for new employees.
                        </p>
                    </div>
                    <div
                        class="p-8 lg:p-12 flex flex-col justify-center bg-green-50/50 dark:bg-green-900/10 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">The Solution</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">
                            We prioritize User Experience (UX) above all. Our lightning-fast, intuitive interface
                            ensures high adoption rates and user satisfaction from day one.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Features Section -->
    <div id="features" class="py-24 bg-white dark:bg-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in-section">
                <h2 class="text-base text-indigo-600 dark:text-indigo-400 font-semibold tracking-wide uppercase">
                    Features</h2>
                <p
                    class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Everything you need to succeed
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 mx-auto">
                    A comprehensive suite of powerful tools designed to scale with your business.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div
                    class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:-translate-y-1 fade-in-section">
                    <div
                        class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center mb-6 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Lightning Fast</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Optimized for speed and performance, ensuring your users have the best experience possible
                        without any lag.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:-translate-y-1 fade-in-section"
                    style="transition-delay: 100ms;">
                    <div
                        class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center mb-6 text-purple-600 dark:text-purple-400 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Secure by Design</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Built with security in mind from the ground up. Your data is protected with enterprise-grade
                        encryption.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:-translate-y-1 fade-in-section"
                    style="transition-delay: 200ms;">
                    <div
                        class="w-14 h-14 bg-pink-100 dark:bg-pink-900/30 rounded-2xl flex items-center justify-center mb-6 text-pink-600 dark:text-pink-400 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Easy to Use</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Intuitive interface that requires no training. Get up and running in minutes, not days.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div
                    class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:-translate-y-1 fade-in-section">
                    <div
                        class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mb-6 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Analytics</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Gain deep insights into your performance with our advanced analytics dashboard.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:-translate-y-1 fade-in-section"
                    style="transition-delay: 100ms;">
                    <div
                        class="w-14 h-14 bg-orange-100 dark:bg-orange-900/30 rounded-2xl flex items-center justify-center mb-6 text-orange-600 dark:text-orange-400 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Team Collaboration</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Built for teams. Share, comment, and collaborate in real-time to get work done faster.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:-translate-y-1 fade-in-section"
                    style="transition-delay: 200ms;">
                    <div
                        class="w-14 h-14 bg-teal-100 dark:bg-teal-900/30 rounded-2xl flex items-center justify-center mb-6 text-teal-600 dark:text-teal-400 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">24/7 Support</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Our dedicated support team is always available to help you resolve any issues quickly.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Pricing Section -->
    <div id="pricing" class="py-24 bg-gray-900 dark:bg-gray-950 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in-section">
                <h2 class="text-base text-indigo-400 font-semibold tracking-wide uppercase">Pricing</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold text-white sm:text-4xl">
                    Simple, transparent pricing
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-400 mx-auto">
                    Choose the plan that fits your needs. No hidden fees.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Basic Plan -->
                <div
                    class="bg-gray-800 dark:bg-gray-900 rounded-2xl p-8 border border-gray-700 hover:border-indigo-500 transition-colors relative fade-in-left">
                    <h3 class="text-xl font-bold text-white mb-4">Starter</h3>
                    <p class="text-gray-400 mb-6">Perfect for individuals and small projects.</p>
                    <div class="flex items-baseline mb-8">
                        <span class="text-4xl font-extrabold text-white">Rp. 1.000.000</span>
                        <span class="text-gray-400 ml-2">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-gray-300">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            5 Projects
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Basic Analytics
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Email Support
                        </li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="block w-full py-3 px-4 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg text-center transition-colors">Get
                        Started</a>
                </div>

                <!-- Pro Plan -->
                <div class="bg-indigo-900/50 dark:bg-indigo-900/30 rounded-2xl p-8 border-2 border-indigo-500 relative transform md:-translate-y-4 shadow-2xl fade-in-section"
                    style="transition-delay: 100ms;">
                    <div
                        class="absolute top-0 right-0 bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-lg uppercase tracking-wider">
                        Popular</div>
                    <h3 class="text-xl font-bold text-white mb-4">Professional</h3>
                    <p class="text-indigo-200 mb-6">For growing teams and businesses.</p>
                    <div class="flex items-baseline mb-8">
                        <span class="text-4xl font-extrabold text-white">Rp. 2.000.000</span>
                        <span class="text-indigo-200 ml-2">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-indigo-100">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-indigo-300 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Unlimited Projects
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-indigo-300 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Advanced Analytics
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-indigo-300 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Priority Support
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-indigo-300 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Team Collaboration
                        </li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="block w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-center transition-colors shadow-lg">Get
                        Started</a>
                </div>

                <!-- Enterprise Plan -->
                <div
                    class="bg-gray-800 dark:bg-gray-900 rounded-2xl p-8 border border-gray-700 hover:border-indigo-500 transition-colors relative fade-in-right">
                    <h3 class="text-xl font-bold text-white mb-4">Enterprise</h3>
                    <p class="text-gray-400 mb-6">Custom solutions for large organizations.</p>
                    <div class="flex items-baseline mb-8">
                        <span class="text-4xl font-extrabold text-white">Custom</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-gray-300">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Dedicated Infrastructure
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Custom Integrations
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            24/7 Dedicated Support
                        </li>
                    </ul>
                    <a href="#"
                        class="block w-full py-3 px-4 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg text-center transition-colors">Contact
                        Sales</a>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-600 dark:bg-indigo-700 py-20 relative overflow-hidden fade-in-section">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-6">
                Ready to transform your business?
            </h2>
            <p class="text-xl text-indigo-100 mb-10">
                Join thousands of satisfied users who have transformed their workflow with Amerta. Start your 14-day
                free trial today.
            </p>
            <a href="{{ route('register') }}"
                class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-full font-bold text-lg hover:bg-indigo-50 transition-colors shadow-xl">
                Create Free Account
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-black text-white py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-1">
                    <span class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                        <span
                            class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">A</span>
                        Amerta
                    </span>
                    <p class="mt-4 text-gray-400 text-sm">
                        Making the world a better place through constructing elegant hierarchies.
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase mb-4">Product</h3>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Features</a>
                        </li>
                        <li><a href="#pricing" class="text-gray-400 hover:text-white transition-colors">Pricing</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors">Integrations</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="#about" class="text-gray-400 hover:text-white transition-colors">About</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Careers</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase mb-4">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Terms</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Security</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} Amerta. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84">
                            </path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">GitHub</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target); // Only animate once
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.fade-in-section, .fade-in-left, .fade-in-right').forEach(section => {
                observer.observe(section);
            });

            // Parallax Effect for Hero Blobs
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const blob1 = document.getElementById('blob-1');
                const blob2 = document.getElementById('blob-2');
                const blob3 = document.getElementById('blob-3');

                if (blob1) blob1.style.transform = `translate(0px, ${scrolled * 0.2}px)`;
                if (blob2) blob2.style.transform = `translate(0px, ${scrolled * 0.3}px)`;
                if (blob3) blob3.style.transform = `translate(0px, ${scrolled * 0.1}px)`;
            });
        });
    </script>
</body>

</html>
