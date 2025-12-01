<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup Bisnis - Amerta AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased">

    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">

        <!-- Background Elements -->
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 pointer-events-none">
            <div
                class="absolute top-20 left-10 w-96 h-96 bg-purple-200 dark:bg-purple-900/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-30">
            </div>
            <div
                class="absolute top-20 right-10 w-96 h-96 bg-indigo-200 dark:bg-indigo-900/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-30">
            </div>
            <div
                class="absolute -bottom-8 left-1/2 w-96 h-96 bg-pink-200 dark:bg-pink-900/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-30">
            </div>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
            <div class="flex justify-center">
                <a href="#"
                    class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 tracking-tight flex items-center gap-2">
                    <span
                        class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xl">A</span>
                    Amerta
                </a>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                Setup Bisnis Anda
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Lengkapi profil bisnis Anda dalam 3 langkah mudah.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl relative z-10" x-data="{
            step: 1,
            totalSteps: 3,
            nextStep() { if (this.step < this.totalSteps) this.step++ },
            prevStep() { if (this.step > 1) this.step-- }
        }">

            <div
                class="bg-white dark:bg-gray-800 py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-gray-100 dark:border-gray-700">

                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-2">
                        <span
                            class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200 dark:text-indigo-200 dark:bg-indigo-900">
                            Langkah <span x-text="step"></span> dari <span x-text="totalSteps"></span>
                        </span>
                        <span class="text-xs font-semibold inline-block text-indigo-600 dark:text-indigo-400">
                            <span x-text="Math.round((step / totalSteps) * 100)"></span>%
                        </span>
                    </div>
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200 dark:bg-indigo-900/50">
                        <div :style="'width: ' + ((step / totalSteps) * 100) + '%'"
                            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-600 transition-all duration-500">
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('setup-bisnis.store') }}">
                    @csrf

                    <!-- Step 1: Basic Info -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">Informasi Dasar
                        </h3>

                        <div class="space-y-6">
                            <!-- Nama Bisnis -->
                            <div>
                                <label for="nama_bisnis"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                                    Bisnis</label>
                                <div class="mt-1">
                                    <input id="nama_bisnis" name="nama_bisnis" type="text" required
                                        value="{{ old('nama_bisnis') }}"
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white transition-colors">
                                </div>
                                @error('nama_bisnis')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Bisnis -->
                            <div>
                                <label for="status_bisnis"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status
                                    Bisnis</label>
                                <div class="mt-1">
                                    <select id="status_bisnis" name="status_bisnis" required
                                        class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg dark:bg-gray-700 dark:text-white transition-colors">
                                        <option value="">Pilih Status</option>
                                        <option value="Baru Mulai"
                                            {{ old('status_bisnis') == 'Baru Mulai' ? 'selected' : '' }}>Baru Mulai
                                        </option>
                                        <option value="Sudah Berjalan"
                                            {{ old('status_bisnis') == 'Sudah Berjalan' ? 'selected' : '' }}>Sudah
                                            Berjalan</option>
                                    </select>
                                </div>
                                @error('status_bisnis')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori Bisnis -->
                            <div>
                                <label for="kategori_bisnis"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori
                                    Bisnis</label>
                                <div class="mt-1">
                                    <input id="kategori_bisnis" name="kategori_bisnis" type="text" required
                                        value="{{ old('kategori_bisnis') }}" placeholder="Contoh: F&B, Fashion, Jasa"
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white transition-colors">
                                </div>
                                @error('kategori_bisnis')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Market & Sales -->
                    <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">Pasar & Penjualan
                        </h3>

                        <div class="space-y-6">
                            <!-- Channel Penjualan -->
                            <div>
                                <label for="channel_penjualan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Channel
                                    Penjualan</label>
                                <div class="mt-1">
                                    <select id="channel_penjualan" name="channel_penjualan" required
                                        class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg dark:bg-gray-700 dark:text-white transition-colors">
                                        <option value="">Pilih Channel</option>
                                        <option value="Online"
                                            {{ old('channel_penjualan') == 'Online' ? 'selected' : '' }}>Online</option>
                                        <option value="Offline"
                                            {{ old('channel_penjualan') == 'Offline' ? 'selected' : '' }}>Offline
                                        </option>
                                        <option value="Hybrid"
                                            {{ old('channel_penjualan') == 'Hybrid' ? 'selected' : '' }}>Hybrid
                                        </option>
                                    </select>
                                </div>
                                @error('channel_penjualan')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Target Pasar -->
                            <div>
                                <label for="target_pasar"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Target
                                    Pasar</label>
                                <div class="mt-1">
                                    <input id="target_pasar" name="target_pasar" type="text" required
                                        value="{{ old('target_pasar') }}"
                                        placeholder="Contoh: Remaja, Profesional, Ibu Rumah Tangga"
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white transition-colors">
                                </div>
                                @error('target_pasar')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Range Omset -->
                            <div>
                                <label for="range_omset"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Range Omset
                                    Bulanan</label>
                                <div class="mt-1">
                                    <select id="range_omset" name="range_omset" required
                                        class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg dark:bg-gray-700 dark:text-white transition-colors">
                                        <option value="">Pilih Range</option>
                                        <option value="< 10 Juta"
                                            {{ old('range_omset') == '< 10 Juta' ? 'selected' : '' }}>
                                            < 10 Juta</option>
                                        <option value="10 - 50 Juta"
                                            {{ old('range_omset') == '10 - 50 Juta' ? 'selected' : '' }}>10 - 50 Juta
                                        </option>
                                        <option value="50 - 100 Juta"
                                            {{ old('range_omset') == '50 - 100 Juta' ? 'selected' : '' }}>50 - 100 Juta
                                        </option>
                                        <option value="> 100 Juta"
                                            {{ old('range_omset') == '> 100 Juta' ? 'selected' : '' }}>> 100 Juta
                                        </option>
                                    </select>
                                </div>
                                @error('range_omset')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Team & Goals -->
                    <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">Tim & Tujuan</h3>

                        <div class="space-y-6">
                            <!-- Jumlah Tim -->
                            <div>
                                <label for="jumlah_tim"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah
                                    Tim</label>
                                <div class="mt-1">
                                    <select id="jumlah_tim" name="jumlah_tim" required
                                        class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg dark:bg-gray-700 dark:text-white transition-colors">
                                        <option value="">Pilih Jumlah</option>
                                        <option value="1 (Sendiri)"
                                            {{ old('jumlah_tim') == '1 (Sendiri)' ? 'selected' : '' }}>1 (Sendiri)
                                        </option>
                                        <option value="2 - 5 Orang"
                                            {{ old('jumlah_tim') == '2 - 5 Orang' ? 'selected' : '' }}>2 - 5 Orang
                                        </option>
                                        <option value="6 - 20 Orang"
                                            {{ old('jumlah_tim') == '6 - 20 Orang' ? 'selected' : '' }}>6 - 20 Orang
                                        </option>
                                        <option value="> 20 Orang"
                                            {{ old('jumlah_tim') == '> 20 Orang' ? 'selected' : '' }}>> 20 Orang
                                        </option>
                                    </select>
                                </div>
                                @error('jumlah_tim')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tujuan Utama -->
                            <div>
                                <label for="tujuan_utama"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tujuan Utama
                                    Bisnis</label>
                                <div class="mt-1">
                                    <input id="tujuan_utama" name="tujuan_utama" type="text" required
                                        value="{{ old('tujuan_utama') }}"
                                        placeholder="Contoh: Scale Up, Automasi, Profitabilitas"
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white transition-colors">
                                </div>
                                @error('tujuan_utama')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Masalah Utama -->
                            <div>
                                <label for="masalah_utama"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Masalah Utama
                                    (Opsional)</label>
                                <div class="mt-1">
                                    <textarea id="masalah_utama" name="masalah_utama" rows="3"
                                        class="appearance-none block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white transition-colors">{{ old('masalah_utama') }}</textarea>
                                </div>
                                @error('masalah_utama')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-8 flex justify-between">
                        <button type="button" x-show="step > 1" @click="prevStep()"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 shadow-sm text-base font-medium rounded-full text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Kembali
                        </button>
                        <div class="flex-1"></div> <!-- Spacer -->
                        <button type="button" x-show="step < totalSteps" @click="nextStep()"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Lanjut
                        </button>
                        <button type="submit" x-show="step === totalSteps" x-cloak
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Selesai & Masuk Dashboard
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>
