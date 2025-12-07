<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup Bisnis - Amerta AI</title>

    @livewireStyles

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-gray-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased transition-colors duration-300 min-h-screen flex items-center justify-center p-4 lg:p-8"
    x-data="setupForm()">

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div
            class="absolute -top-40 -left-40 w-96 h-96 bg-brand-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 dark:opacity-10 animate-blob">
        </div>
        <div
            class="absolute top-0 -right-20 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 dark:opacity-10 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-40 left-20 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 dark:opacity-10 animate-blob animation-delay-4000">
        </div>
    </div>

    <div
        class="relative w-full max-w-6xl h-[85vh] max-h-[800px] bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row border border-gray-100 dark:border-slate-800 transition-all duration-300">

        <div x-show="isSubmitting" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            class="absolute inset-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md flex flex-col items-center justify-center text-center p-8"
            x-cloak>

            <div class="relative w-24 h-24 mb-8">
                <div class="absolute inset-0 rounded-full border-4 border-gray-100 dark:border-slate-800"></div>
                <div class="absolute inset-0 rounded-full border-4 border-brand-500 border-t-transparent animate-spin">
                </div>
                <div class="absolute inset-0 flex items-center justify-center animate-pulse-slow">
                    <svg class="w-10 h-10 text-brand-600 dark:text-brand-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 19H22L12 2Z" />
                    </svg>
                </div>
            </div>

            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2" x-text="loadingText"></h3>
            <p class="text-slate-500 dark:text-slate-400 max-w-md animate-pulse">Mohon tunggu, kami sedang
                mengonfigurasi workspace bisnis Anda agar sesuai dengan preferensi yang dipilih.</p>
        </div>

        <div class="w-full lg:w-7/12 flex flex-col h-full relative z-10">

            <div
                class="px-8 py-6 flex justify-between items-center border-b border-gray-100 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm z-20">
                <a href="#" class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-600 to-brand-700 flex items-center justify-center text-white shadow-lg shadow-brand-500/20">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 19H22L12 2Z" class="fill-white/20" />
                            <path d="M12 6L4.5 19H19.5L12 6Z" stroke="currentColor" stroke-width="2"
                                stroke-linejoin="round" />
                            <path d="M12 11L8.5 17H15.5L12 11Z" fill="currentColor" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Amerta<span
                            class="text-brand-600 dark:text-brand-400">.AI</span></span>
                </a>

                <button @click="toggleTheme()"
                    class="p-2.5 rounded-full text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto scrollbar-hide px-8 py-6 pb-20">

                <div class="mb-6">
                    <a href="{{ route('dashboard-selection') }}"
                        class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400 transition-colors group">
                        <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                        Kembali ke Pemilihan Peran
                    </a>
                </div>

                <div class="mb-10">
                    <div
                        class="flex justify-between text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2.5 uppercase tracking-wide">
                        <span x-text="'Langkah ' + step + ' dari 3'"></span>
                        <span x-text="stepTitle"></span>
                    </div>
                    <div class="h-1.5 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-brand-600 to-indigo-500 transition-all duration-700 ease-out shadow-[0_0_12px_rgba(99,102,241,0.4)]"
                            :style="'width: ' + ((step / 3) * 100) + '%'"></div>
                    </div>
                </div>

                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2 tracking-tight"
                        x-text="stepHeader"></h1>
                    <p class="text-slate-500 dark:text-slate-400" x-text="stepDesc"></p>
                </div>

                <form onsubmit="event.preventDefault();" class="space-y-6">

                    <!-- STEP 1: IDENTITAS BISNIS -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                        <div class="group">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama
                                Bisnis</label>
                            <input type="text" x-model="formData.nama_bisnis" placeholder="Contoh: Kopi Senja"
                                :class="{ '!border-red-500 focus:!ring-red-500': errors.nama_bisnis }"
                                class="w-full px-4 py-3.5 rounded-xl bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-600 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-all">
                            <span x-show="errors.nama_bisnis"
                                class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg> Nama bisnis wajib diisi.
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status
                                Bisnis</label>
                            <div class="grid grid-cols-2 gap-4">
                                <template x-for="status in ['Baru Mulai', 'Sudah Berjalan']">
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="status_bisnis" :value="status"
                                            x-model="formData.status_bisnis" class="peer sr-only">
                                        <div :class="{ '!border-red-500': errors.status_bisnis }"
                                            class="px-4 py-4 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 text-center text-slate-600 dark:text-slate-400 group-hover:bg-gray-50 dark:group-hover:bg-slate-800 peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:peer-checked:bg-brand-900/20 peer-checked:text-brand-700 dark:peer-checked:text-brand-300 transition-all shadow-sm">
                                            <span x-text="status" class="text-sm font-medium"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                            <span x-show="errors.status_bisnis" class="text-xs text-red-500 mt-1.5 block">Pilih status
                                bisnis.</span>

                            <!-- LOGIKA MASALAH UTAMA (Hanya Muncul Jika 'Sudah Berjalan') -->
                            <div x-show="formData.status_bisnis === 'Sudah Berjalan'"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0" class="mt-4">
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Masalah
                                    Utama Saat Ini</label>
                                <textarea x-model="formData.masalah_utama" rows="3"
                                    placeholder="Contoh: Pembukuan berantakan, stok sering hilang, omset stuck..."
                                    :class="{ '!border-red-500': errors.masalah_utama }"
                                    class="w-full px-4 py-3.5 rounded-xl bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-600 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-all resize-none"></textarea>
                                <span x-show="errors.masalah_utama" class="text-xs text-red-500 mt-1.5 block">Mohon
                                    ceritakan kendala bisnis Anda.</span>
                            </div>
                        </div>

                        <div x-data="{ isManual: false }">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Kategori
                                Bisnis</label>
                            <div class="relative">
                                <select x-model="formData.kategori_bisnis"
                                    @change="isManual = formData.kategori_bisnis === 'Lainnya'"
                                    :class="{ '!border-red-500': errors.kategori_bisnis }"
                                    class="w-full px-4 py-3.5 rounded-xl bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 appearance-none transition-all cursor-pointer">
                                    <option value="" disabled>Pilih Kategori</option>
                                    <option value="F&B">F&B (Makanan & Minuman)</option>
                                    <option value="Retail">Retail</option>
                                    <option value="Fashion">Fashion</option>
                                    <option value="Jasa">Jasa / Service</option>
                                    <option value="Produk Digital">Produk Digital & Kreatif</option>
                                    <option value="Lainnya">Lainnya...</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>

                            <div x-show="isManual" x-collapse class="mt-3">
                                <input type="text" x-model="formData.kategori_manual"
                                    placeholder="Sebutkan kategori spesifik..."
                                    :class="{ '!border-red-500': errors.kategori_manual }"
                                    class="w-full px-4 py-3.5 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-all">
                            </div>
                            <span x-show="errors.kategori_bisnis" class="text-xs text-red-500 mt-1.5 block">Pilih
                                kategori bisnis.</span>
                        </div>
                    </div>

                    <!-- STEP 2: TARGET & PENJUALAN -->
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" x-cloak>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Channel
                                Penjualan Utama</label>
                            <div class="grid grid-cols-3 gap-3">
                                <template x-for="channel in ['Online', 'Offline', 'Hybrid']">
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="channel_penjualan" :value="channel"
                                            x-model="formData.channel_penjualan" class="peer sr-only">
                                        <div :class="{ '!border-red-500': errors.channel_penjualan }"
                                            class="px-2 py-3.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 text-center text-slate-600 dark:text-slate-400 group-hover:bg-gray-50 dark:group-hover:bg-slate-800 peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:peer-checked:bg-brand-900/20 peer-checked:text-brand-700 dark:peer-checked:text-brand-300 transition-all shadow-sm">
                                            <span x-text="channel" class="text-sm font-medium"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                            <span x-show="errors.channel_penjualan" class="text-xs text-red-500 mt-1.5 block">Pilih
                                channel penjualan.</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Target
                                Pasar</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <input type="text" x-model="formData.target_pasar"
                                    placeholder="Misal: Mahasiswa, Pekerja Kantoran"
                                    :class="{ '!border-red-500': errors.target_pasar }"
                                    class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-600 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-all">
                            </div>
                            <span x-show="errors.target_pasar" class="text-xs text-red-500 mt-1.5 block">Target pasar
                                wajib diisi.</span>
                        </div>

                        <!-- INPUT ESTIMASI OMSET (FORMATTED) -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estimasi
                                Omset Bulanan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-semibold">Rp</span>
                                </div>
                                <!-- Gunakan :value dan @input agar formatter bekerja saat ngetik -->
                                <input type="text" :value="formData.range_omset" @input="formatOmset($event)"
                                    placeholder="Contoh: 50.000.000"
                                    :class="{ '!border-red-500': errors.range_omset }"
                                    class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-600 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-all">
                            </div>
                            <p class="text-xs text-slate-500 mt-1.5">Masukkan angka kasar/estimasi omset Anda saat ini.
                            </p>
                            <span x-show="errors.range_omset" class="text-xs text-red-500 mt-1.5 block">Estimasi omset
                                wajib diisi.</span>
                        </div>
                    </div>

                    <!-- STEP 3: TUJUAN -->
                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" x-cloak>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tujuan
                                Utama Tahun Ini</label>
                            <textarea x-model="formData.tujuan_utama" rows="4" placeholder="Apa goal terbesar bisnis Anda saat ini?"
                                :class="{ '!border-red-500': errors.tujuan_utama }"
                                class="w-full px-4 py-3.5 rounded-xl bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-600 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-all resize-none"></textarea>
                            <span x-show="errors.tujuan_utama" class="text-xs text-red-500 mt-1.5 block">Tujuan wajib
                                diisi agar AI lebih akurat.</span>
                        </div>
                    </div>
                </form>
            </div>

            <div
                class="px-8 py-6 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 z-20 rounded-bl-3xl">
                <div class="flex items-center justify-between">
                    <button type="button" x-show="step > 1" @click="prevStep()"
                        class="text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-medium text-sm transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </button>
                    <div x-show="step === 1"></div> <button type="button"
                        @click="step < 3 ? nextStep() : submitForm()"
                        class="px-8 py-3 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 text-white rounded-xl font-semibold shadow-lg shadow-brand-500/30 transition-all transform active:scale-95 flex items-center gap-2">
                        <span x-text="step === 3 ? 'Selesai & Proses' : 'Lanjut'"></span>
                        <svg x-show="step < 3" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="hidden lg:block lg:w-5/12 relative bg-slate-900 overflow-hidden">
            <template x-for="(img, index) in images" :key="index">
                <div x-show="step === index + 1" x-transition:enter="transition ease-out duration-1000"
                    x-transition:enter-start="opacity-0 scale-110" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-700" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="absolute inset-0">
                    <img :src="img" class="w-full h-full object-cover opacity-60">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent">
                    </div>
                </div>
            </template>

            <div class="absolute bottom-0 left-0 right-0 p-12 z-20">
                <div class="overflow-hidden mb-4">
                    <span x-show="true" x-transition:enter="transition ease-out delay-300 duration-500"
                        x-transition:enter-start="translate-y-full opacity-0"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase bg-white/10 text-white backdrop-blur-md border border-white/20">
                        Amerta Intelligence
                    </span>
                </div>
                <h2 class="text-3xl font-bold text-white mb-4 leading-tight" x-text="stepQuote.title"></h2>
                <p class="text-slate-300 leading-relaxed text-sm lg:text-base opacity-90" x-text="stepQuote.desc"></p>
            </div>

            <div class="absolute top-0 right-0 p-8 opacity-20">
                <svg width="80" height="80" viewBox="0 0 100 100" fill="none">
                    <circle cx="50" cy="50" r="48" stroke="white" stroke-width="2"
                        stroke-dasharray="10 10" />
                    <circle cx="50" cy="50" r="30" fill="white" />
                </svg>
            </div>
        </div>
    </div>

    <script>
        function setupForm() {
            return {
                step: 1,
                darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                    '(prefers-color-scheme: dark)').matches),
                isSubmitting: false,
                loadingText: 'Menganalisis Data...',
                errors: {},

                formData: {
                    nama_bisnis: '',
                    status_bisnis: '',
                    kategori_bisnis: '',
                    kategori_manual: '',
                    channel_penjualan: '',
                    target_pasar: '',
                    range_omset: '',
                    tujuan_utama: ''
                },

                images: [
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=800&q=80'
                ],

                get stepTitle() {
                    return ['Identitas Bisnis', 'Market', 'Goals'][this.step - 1];
                },
                get stepHeader() {
                    return ['Informasi Dasar', 'Target & Penjualan', 'Tujuan'][this.step - 1];
                },
                get stepDesc() {
                    return ['Mulai dengan nama dan kategori bisnis Anda.',
                        'Seberapa besar jangkauan pasar Anda saat ini?', 'Ceritakan tentang visi bisnis Anda.'
                    ][this.step - 1];
                },

                get stepQuote() {
                    const quotes = [{
                            title: "Mulai Fondasi Digital.",
                            desc: "Setup yang akurat membantu AI kami memahami konteks bisnis Anda secara mendalam."
                        },
                        {
                            title: "Analisa Tanpa Batas.",
                            desc: "Kami menggunakan data pasar global untuk memberikan insight lokal yang relevan."
                        },
                        {
                            title: "Skalabilitas Bisnis.",
                            desc: "Siapkan tim Anda untuk pertumbuhan eksponensial dengan bantuan teknologi."
                        }
                    ];
                    return quotes[this.step - 1];
                },

                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                    if (this.darkMode) document.documentElement.classList.add('dark');
                    else document.documentElement.classList.remove('dark');
                },

                validateStep() {
                    this.errors = {};
                    let isValid = true;
                    const f = this.formData;

                    if (this.step === 1) {
                        if (!f.nama_bisnis.trim()) {
                            this.errors.nama_bisnis = true;
                            isValid = false;
                        }
                        if (!f.status_bisnis) {
                            this.errors.status_bisnis = true;
                            isValid = false;
                        }
                        if (!f.kategori_bisnis) {
                            this.errors.kategori_bisnis = true;
                            isValid = false;
                        }
                        if (f.kategori_bisnis === 'Lainnya' && !f.kategori_manual.trim()) {
                            this.errors.kategori_manual = true;
                            isValid = false;
                        }
                    } else if (this.step === 2) {
                        if (!f.channel_penjualan) {
                            this.errors.channel_penjualan = true;
                            isValid = false;
                        }
                        if (!f.target_pasar.trim()) {
                            this.errors.target_pasar = true;
                            isValid = false;
                        }
                        // range_omset sekarang input manual, validasi string kosong saja
                        if (!f.range_omset.trim()) {
                            this.errors.range_omset = true;
                            isValid = false;
                        }
                    } else if (this.step === 3) {
                        if (!f.tujuan_utama.trim()) {
                            this.errors.tujuan_utama = true;
                            isValid = false;
                        }
                    }
                    return isValid;
                },

                nextStep() {
                    if (this.validateStep()) this.step++;
                },
                prevStep() {
                    if (this.step > 1) this.step--;
                },

                // FUNGSI FORMATTER UANG
                formatOmset(e) {
                    let value = e.target.value;
                    // Hapus semua karakter non-angka
                    let number = value.replace(/\D/g, '');

                    if (number === '') {
                        this.formData.range_omset = '';
                    } else {
                        // Format ke Rupiah (dengan titik)
                        this.formData.range_omset = new Intl.NumberFormat('id-ID').format(number);
                    }
                    // Paksa update value input
                    e.target.value = this.formData.range_omset;
                },

                async submitForm() {
                    if (!this.validateStep()) return;

                    this.isSubmitting = true;
                    setTimeout(() => this.loadingText = 'Menyiapkan Database...', 1000);
                    setTimeout(() => this.loadingText = 'Mempersiapkan Dashboard Anda...', 2000);
                    await new Promise(resolve => setTimeout(resolve, 3000));

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('setup-bisnis.store') }}';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    Object.keys(this.formData).forEach(key => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;

                        // BERSIHKAN TITIK SEBELUM KIRIM KE SERVER
                        if (key === 'range_omset') {
                            input.value = this.formData[key].replace(/\./g, '');
                        } else {
                            input.value = this.formData[key];
                        }

                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>

    @livewireScripts
</body>

</html>
