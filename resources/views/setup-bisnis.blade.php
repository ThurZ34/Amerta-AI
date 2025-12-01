<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup Bisnis - Amerta AI</title>

    <!-- PENTING: Config harus didefinisikan SEBELUM memuat script Tailwind CDN -->
    <script>
        // Konfigurasi Tailwind
        window.tailwindConfig = {
            darkMode: 'class', // Mengaktifkan dark mode via class 'dark'
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        },
                        secondary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                            950: '#2e1065',
                        },
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 3s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-20px)'
                            },
                        }
                    }
                }
            }
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = window.tailwindConfig;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #475569;
        }

        /* Transitions */
        .fade-enter-active,
        .fade-leave-active {
            transition: opacity 0.5s ease;
        }

        .fade-enter-from,
        .fade-leave-to {
            opacity: 0;
        }

        /* Blob Animations */
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

        /* Background Grid Pattern */
        .bg-grid-pattern {
            background-image: radial-gradient(#6366f1 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>

<body
    class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 antialiased selection:bg-secondary-500 selection:text-white overflow-x-hidden transition-colors duration-300"
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
    }" x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'));
    if (darkMode) document.documentElement.classList.add('dark');">

    <!-- Main Container -->
    <div class="min-h-screen flex items-center justify-center p-4 lg:p-8 relative">

        <!-- NEW: Grid Pattern Overlay -->
        <div class="fixed inset-0 z-0 pointer-events-none opacity-[0.03] dark:opacity-[0.05] bg-grid-pattern"></div>

        <!-- Ambient Background (Purple/Indigo Theme) -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div
                class="absolute top-0 left-0 w-[500px] h-[500px] bg-secondary-300 dark:bg-secondary-900/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-40 animate-blob">
            </div>
            <div
                class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary-300 dark:bg-primary-900/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-40 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-32 left-20 w-[500px] h-[500px] bg-pink-300 dark:bg-pink-900/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-40 animate-blob animation-delay-4000">
            </div>
        </div>

        <!-- Card Container -->
        <div class="w-full max-w-[1200px] bg-white dark:bg-gray-900/80 backdrop-blur-xl rounded-3xl shadow-2xl dark:shadow-secondary-900/20 border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col lg:flex-row relative z-10 min-h-[700px] transition-colors duration-300"
            x-data="{
                step: 1,
                totalSteps: 3,
                errors: {}, // Object untuk menyimpan error validasi

                formData: {
                    nama_bisnis: '',
                    status_bisnis: '',
                    kategori_bisnis: '',
                    kategori_manual: '',
                    channel_penjualan: '',
                    target_pasar: '',
                    range_omset: '',
                    jumlah_tim: '',
                    tujuan_utama: ''
                },

                images: [
                    'https://images.unsplash.com/photo-1497215728101-856f4ea42174?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                    'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'
                ],

                // Fungsi Validasi
                validateStep() {
                    this.errors = {}; // Reset errors
                    let isValid = true;

                    if (this.step === 1) {
                        if (!this.formData.nama_bisnis.trim()) {
                            this.errors.nama_bisnis = true;
                            isValid = false;
                        }
                        if (!this.formData.status_bisnis) {
                            this.errors.status_bisnis = true;
                            isValid = false;
                        }
                        if (!this.formData.kategori_bisnis) {
                            this.errors.kategori_bisnis = true;
                            isValid = false;
                        }
                        if (this.formData.kategori_bisnis === 'Lainnya' && !this.formData.kategori_manual.trim()) {
                            this.errors.kategori_manual = true;
                            isValid = false;
                        }
                    } else if (this.step === 2) {
                        if (!this.formData.channel_penjualan) {
                            this.errors.channel_penjualan = true;
                            isValid = false;
                        }
                        if (!this.formData.target_pasar.trim()) {
                            this.errors.target_pasar = true;
                            isValid = false;
                        }
                        if (!this.formData.range_omset) {
                            this.errors.range_omset = true;
                            isValid = false;
                        }
                    } else if (this.step === 3) {
                        if (!this.formData.jumlah_tim) {
                            this.errors.jumlah_tim = true;
                            isValid = false;
                        }
                        if (!this.formData.tujuan_utama.trim()) {
                            this.errors.tujuan_utama = true;
                            isValid = false;
                        }
                    }

                    return isValid;
                },

                nextStep() {
                    // Cek validasi sebelum lanjut
                    if (this.validateStep()) {
                        if (this.step < this.totalSteps) this.step++;
                    }
                },

                prevStep() { if (this.step > 1) this.step-- },

                get activeImage() { return this.images[this.step - 1]; },

                get stepTitle() {
                    switch (this.step) {
                        case 1:
                            return 'Informasi Dasar';
                        case 2:
                            return 'Pasar & Penjualan';
                        case 3:
                            return 'Tim & Tujuan';
                        default:
                            return '';
                    }
                },

                get stepDesc() {
                    switch (this.step) {
                        case 1:
                            return 'Mari mulai dengan identitas bisnis Anda.';
                        case 2:
                            return 'Seberapa besar jangkauan bisnis Anda saat ini?';
                        case 3:
                            return 'Siapa yang menjalankan dan kemana arahnya?';
                        default:
                            return '';
                    }
                },

                submitForm() {
                    if (!this.validateStep()) {
                        return;
                    }

                    // Create a form element and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('setup-bisnis.store') }}';

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // Add all form data
                    Object.keys(this.formData).forEach(key => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = this.formData[key];
                        form.appendChild(input);
                    });

                    // Append to body and submit
                    document.body.appendChild(form);
                    form.submit();
                }
            }">

            <!-- Left Side: Form Content -->
            <div class="w-full lg:w-7/12 flex flex-col h-full relative">

                <!-- Header Logo & Theme Toggle -->
                <div class="px-8 pt-8 pb-4 flex justify-between items-center">
                    <a href="#" class="flex items-center gap-3 group">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-600 to-secondary-600 text-white flex items-center justify-center shadow-lg shadow-secondary-500/30 group-hover:scale-105 transition-all duration-300">
                            <!-- SVG Logo Modern -->
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 19H22L12 2Z" class="fill-white/20" />
                                <path d="M12 6L4.5 19H19.5L12 6Z" stroke="currentColor" stroke-width="2"
                                    stroke-linejoin="round" />
                                <path d="M12 11L8.5 17H15.5L12 11Z" fill="currentColor" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Amerta<span
                                class="text-secondary-600 dark:text-secondary-400">.AI</span></span>
                    </a>

                    <!-- Theme Toggle Button -->
                    <button @click="toggleTheme()"
                        class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors focus:outline-none ring-1 ring-transparent focus:ring-secondary-500">
                        <!-- Sun -->
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <!-- Moon -->
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Progress Bar Compact -->
                <div class="px-8 mt-2">
                    <div
                        class="flex items-center justify-between text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                        <span>Langkah <span x-text="step"></span> dari 3</span>
                        <span x-text="Math.round((step / 3) * 100) + '%'"></span>
                    </div>
                    <div class="h-1.5 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-primary-600 to-secondary-600 transition-all duration-500 ease-out rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]"
                            :style="'width: ' + ((step / 3) * 100) + '%'"></div>
                    </div>
                </div>

                <!-- Form Scrollable Area -->
                <div class="flex-1 overflow-y-auto custom-scrollbar px-8 py-6 pb-20">

                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stepTitle"></h2>
                        <p class="text-gray-500 dark:text-gray-400 mt-1" x-text="stepDesc"></p>
                    </div>

                    <form onsubmit="event.preventDefault();" class="space-y-6">

                        <!-- STEP 1 -->
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nama
                                    Bisnis</label>
                                <input type="text" x-model="formData.nama_bisnis" placeholder="Contoh: Kopi Senja"
                                    :class="errors.nama_bisnis ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' :
                                        'border-gray-200 dark:border-gray-700 focus:ring-secondary-500/20 focus:border-secondary-500'"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border rounded-xl focus:bg-white dark:focus:bg-gray-900 focus:ring-2 text-gray-900 dark:text-white transition-all outline-none placeholder:text-gray-400 dark:placeholder:text-gray-600">
                                <p x-show="errors.nama_bisnis" class="text-red-500 text-xs mt-1">Nama bisnis wajib
                                    diisi.</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Status
                                    Bisnis</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status_bisnis" value="Baru Mulai"
                                            x-model="formData.status_bisnis" class="peer sr-only">
                                        <div :class="errors.status_bisnis ? 'border-red-500' :
                                            'border-gray-200 dark:border-gray-700 peer-checked:border-secondary-500 dark:peer-checked:border-secondary-400'"
                                            class="p-4 rounded-xl border bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-750 peer-checked:bg-secondary-50 dark:peer-checked:bg-secondary-900/20 peer-checked:text-secondary-700 dark:peer-checked:text-secondary-300 text-gray-600 dark:text-gray-400 transition-all text-center">
                                            <div class="text-sm font-medium">Baru Mulai</div>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status_bisnis" value="Sudah Berjalan"
                                            x-model="formData.status_bisnis" class="peer sr-only">
                                        <div :class="errors.status_bisnis ? 'border-red-500' :
                                            'border-gray-200 dark:border-gray-700 peer-checked:border-secondary-500 dark:peer-checked:border-secondary-400'"
                                            class="p-4 rounded-xl border bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-750 peer-checked:bg-secondary-50 dark:peer-checked:bg-secondary-900/20 peer-checked:text-secondary-700 dark:peer-checked:text-secondary-300 text-gray-600 dark:text-gray-400 transition-all text-center">
                                            <div class="text-sm font-medium">Sudah Berjalan</div>
                                        </div>
                                    </label>
                                </div>
                                <p x-show="errors.status_bisnis" class="text-red-500 text-xs mt-1">Pilih status bisnis.
                                </p>
                            </div>

                            <div x-data="{ isManual: false }">
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Kategori
                                    Bisnis</label>
                                <select x-model="formData.kategori_bisnis"
                                    @change="isManual = formData.kategori_bisnis === 'Lainnya'"
                                    :class="errors.kategori_bisnis ?
                                        'border-red-500 focus:ring-red-500/20 focus:border-red-500' :
                                        'border-gray-200 dark:border-gray-700 focus:ring-secondary-500/20 focus:border-secondary-500'"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border rounded-xl focus:bg-white dark:focus:bg-gray-900 focus:ring-2 text-gray-900 dark:text-white transition-all outline-none appearance-none">
                                    <option value="" disabled class="bg-white dark:bg-gray-800">Pilih Kategori
                                    </option>
                                    <option value="F&B" class="bg-white dark:bg-gray-800">F&B (Makanan & Minuman)
                                    </option>
                                    <option value="Retail" class="bg-white dark:bg-gray-800">Retail</option>
                                    <option value="Fashion" class="bg-white dark:bg-gray-800">Fashion</option>
                                    <option value="Jasa" class="bg-white dark:bg-gray-800">Jasa</option>
                                    <option value="Produk Digital" class="bg-white dark:bg-gray-800">Produk Digital &
                                        Kreatif</option>
                                    <option value="Lainnya" class="bg-white dark:bg-gray-800">Lainnya...</option>
                                </select>
                                <p x-show="errors.kategori_bisnis" class="text-red-500 text-xs mt-1">Pilih kategori.
                                </p>

                                <div x-show="isManual" x-collapse class="mt-3">
                                    <input type="text" x-model="formData.kategori_manual"
                                        placeholder="Sebutkan kategori spesifik..."
                                        :class="errors.kategori_manual ?
                                            'border-red-500 focus:ring-red-500/20 focus:border-red-500' :
                                            'border-gray-300 dark:border-gray-600 focus:ring-secondary-500/20 focus:border-secondary-500'"
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border rounded-xl focus:ring-2 text-gray-900 dark:text-white transition-all outline-none">
                                    <p x-show="errors.kategori_manual" class="text-red-500 text-xs mt-1">Kategori
                                        manual wajib diisi.</p>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 2 -->
                        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5" x-cloak>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Channel
                                    Penjualan Utama</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <template x-for="channel in ['Online', 'Offline', 'Hybrid']">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="channel_penjualan" :value="channel"
                                                x-model="formData.channel_penjualan" class="peer sr-only">
                                            <div :class="errors.channel_penjualan ? 'border-red-500' :
                                                'border-gray-200 dark:border-gray-700 peer-checked:border-secondary-500 dark:peer-checked:border-secondary-400'"
                                                class="py-3 px-2 rounded-xl border bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-750 peer-checked:bg-secondary-50 dark:peer-checked:bg-secondary-900/20 peer-checked:text-secondary-700 dark:peer-checked:text-secondary-300 text-gray-600 dark:text-gray-400 transition-all text-center">
                                                <span class="text-sm font-medium" x-text="channel"></span>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                                <p x-show="errors.channel_penjualan" class="text-red-500 text-xs mt-1">Pilih channel
                                    penjualan.</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Target
                                    Pasar</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" x-model="formData.target_pasar"
                                        placeholder="Misal: Mahasiswa, Pekerja Kantoran"
                                        :class="errors.target_pasar ?
                                            'border-red-500 focus:ring-red-500/20 focus:border-red-500' :
                                            'border-gray-200 dark:border-gray-700 focus:ring-secondary-500/20 focus:border-secondary-500'"
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-800 border rounded-xl focus:bg-white dark:focus:bg-gray-900 focus:ring-2 text-gray-900 dark:text-white transition-all outline-none placeholder:text-gray-400 dark:placeholder:text-gray-600">
                                </div>
                                <p x-show="errors.target_pasar" class="text-red-500 text-xs mt-1">Target pasar wajib
                                    diisi.</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Estimasi
                                    Omset Bulanan</label>
                                <select x-model="formData.range_omset"
                                    :class="errors.range_omset ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' :
                                        'border-gray-200 dark:border-gray-700 focus:ring-secondary-500/20 focus:border-secondary-500'"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border rounded-xl focus:bg-white dark:focus:bg-gray-900 focus:ring-2 text-gray-900 dark:text-white transition-all outline-none appearance-none cursor-pointer">
                                    <option value="" disabled class="bg-white dark:bg-gray-800">Pilih Range
                                    </option>
                                    <option value="< 10 Juta" class="bg-white dark:bg-gray-800">&lt; 10 Juta</option>
                                    <option value="10 - 50 Juta" class="bg-white dark:bg-gray-800">10 - 50 Juta
                                    </option>
                                    <option value="50 - 100 Juta" class="bg-white dark:bg-gray-800">50 - 100 Juta
                                    </option>
                                    <option value="> 100 Juta" class="bg-white dark:bg-gray-800">&gt; 100 Juta
                                    </option>
                                </select>
                                <p x-show="errors.range_omset" class="text-red-500 text-xs mt-1">Pilih range omset.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 3 -->
                        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5" x-cloak>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Ukuran
                                    Tim</label>
                                <select x-model="formData.jumlah_tim"
                                    :class="errors.jumlah_tim ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' :
                                        'border-gray-200 dark:border-gray-700 focus:ring-secondary-500/20 focus:border-secondary-500'"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border rounded-xl focus:bg-white dark:focus:bg-gray-900 focus:ring-2 text-gray-900 dark:text-white transition-all outline-none appearance-none">
                                    <option value="" disabled class="bg-white dark:bg-gray-800">Berapa orang
                                        dalam tim?</option>
                                    <option value="1" class="bg-white dark:bg-gray-800">Solo Fighter (Sendiri)
                                    </option>
                                    <option value="2-5" class="bg-white dark:bg-gray-800">Micro Team (2 - 5)
                                    </option>
                                    <option value="6-20" class="bg-white dark:bg-gray-800">Growing (6 - 20)</option>
                                    <option value=">20" class="bg-white dark:bg-gray-800">Company (> 20)</option>
                                </select>
                                <p x-show="errors.jumlah_tim" class="text-red-500 text-xs mt-1">Pilih ukuran tim.</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Tujuan
                                    Utama Tahun Ini</label>
                                <textarea x-model="formData.tujuan_utama" rows="3"
                                    placeholder="Apa yang ingin Anda capai? (Misal: Membuka cabang baru)"
                                    :class="errors.tujuan_utama ? 'border-red-500 focus:ring-red-500/20 focus:border-red-500' :
                                        'border-gray-200 dark:border-gray-700 focus:ring-secondary-500/20 focus:border-secondary-500'"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border rounded-xl focus:bg-white dark:focus:bg-gray-900 focus:ring-2 text-gray-900 dark:text-white transition-all outline-none resize-none placeholder:text-gray-400 dark:placeholder:text-gray-600"></textarea>
                                <p x-show="errors.tujuan_utama" class="text-red-500 text-xs mt-1">Tujuan utama wajib
                                    diisi.</p>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- Footer Buttons -->
                <div
                    class="px-8 py-6 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 rounded-b-3xl">
                    <div class="flex justify-between items-center mb-4">
                        <button type="button" x-show="step > 1" @click="prevStep()"
                            class="text-gray-500 dark:text-gray-400 font-medium hover:text-gray-800 dark:hover:text-gray-200 transition-colors px-4 py-2">
                            Kembali
                        </button>
                        <div x-show="step === 1" class="flex-1"></div> <!-- Spacer -->

                        <button type="button" x-show="step < totalSteps" @click="nextStep()"
                            class="group relative inline-flex items-center justify-center px-8 py-3 text-base font-semibold text-white transition-all duration-200 bg-gradient-to-r from-primary-600 to-secondary-600 rounded-xl hover:shadow-lg hover:shadow-secondary-500/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-600">
                            Lanjut
                            <svg class="w-5 h-5 ml-2 -mr-1 transition-transform group-hover:translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>

                        <button type="button" x-show="step === totalSteps" @click="submitForm()" x-cloak
                            class="group relative inline-flex items-center justify-center px-8 py-3 text-base font-semibold text-white transition-all duration-200 bg-gradient-to-r from-primary-600 to-secondary-600 rounded-xl hover:shadow-lg hover:shadow-secondary-500/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-600">
                            Selesai
                        </button>
                    </div>

                    <!-- NEW: Trust Badge / Help Text -->
                    <div
                        class="flex items-center justify-center text-xs text-gray-400 dark:text-gray-500 gap-4 mt-2 border-t border-dashed border-gray-100 dark:border-gray-800 pt-4">
                        <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-green-500"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg> Data Encrypted & Secure</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-700"></span>
                        <a href="#" class="hover:text-primary-500 transition-colors">Butuh bantuan?</a>
                    </div>
                </div>
            </div>

            <!-- Right Side: Dynamic Image Section -->
            <div class="hidden lg:block lg:w-5/12 relative bg-gray-100 dark:bg-gray-800 overflow-hidden">
                <!-- Background Transition Logic -->
                <template x-for="(img, index) in images" :key="index">
                    <div x-show="step === index + 1" x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0"
                        class="absolute inset-0 w-full h-full">

                        <img :src="img" class="w-full h-full object-cover" alt="Illustration">

                        <!-- Modern Overlay Gradient (Purple Tinted) -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-gray-900 via-secondary-900/40 to-transparent opacity-90">
                        </div>

                        <!-- Floating Text/Quote per Step -->
                        <div class="absolute bottom-10 left-8 right-8 text-white z-20">
                            <div class="overflow-hidden mb-2">
                                <span
                                    class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md border border-white/30 rounded-full text-xs font-medium tracking-wider uppercase"
                                    x-show="step === index + 1"
                                    x-transition:enter="transition ease-out delay-300 duration-500 transform"
                                    x-transition:enter-start="translate-y-full opacity-0"
                                    x-transition:enter-end="translate-y-0 opacity-100">
                                    Amerta Insight
                                </span>
                            </div>
                            <h3 class="text-2xl font-bold leading-tight mb-2"
                                x-text="index === 0 ? 'Mulai Perjalanan Digital Anda.' : (index === 1 ? 'Analisa Pasar Lebih Dalam.' : 'Bangun Tim Impian Anda.')">
                            </h3>
                            <p class="text-gray-200 text-sm opacity-90">
                                Setup profil yang lengkap membantu AI kami memberikan rekomendasi strategi bisnis yang
                                95% lebih akurat.
                            </p>
                        </div>
                    </div>
                </template>

                <!-- Decorative Pattern -->
                <div class="absolute top-0 right-0 p-6 opacity-30 z-10">
                    <svg width="64" height="64" viewBox="0 0 64 64" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="32" cy="32" r="32" fill="white" fill-opacity="0.2" />
                        <circle cx="32" cy="32" r="20" stroke="white" stroke-width="2" />
                    </svg>
                </div>
            </div>

            <!-- Mobile Banner Image (Visible only on < lg screens) -->
            <div class="lg:hidden w-full h-48 relative overflow-hidden bg-gray-900">
                <template x-for="(img, index) in images" :key="index">
                    <img :src="img" x-show="step === index + 1"
                        class="absolute inset-0 w-full h-full object-cover opacity-60">
                </template>
                <!-- Purple Overlay for mobile banner -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary-900/80 to-primary-900/80"></div>

                <div class="absolute inset-0 flex items-center justify-center relative z-10">
                    <h1 class="text-white font-bold text-2xl tracking-wide">Amerta<span
                            class="text-secondary-400">.AI</span></h1>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
