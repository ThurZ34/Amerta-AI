@extends('layouts.app')

@section('header', 'Manajemen Produk')

@section('content')
    <div class="py-8 w-full" x-data="{
        createModalOpen: {{ $errors->any() && !session('success') ? 'true' : 'false' }},
        editModalOpen: false,
        deleteModalOpen: false,
        // Default Data
        selectedProduk: { id: null, nama_produk: '', modal: 0, harga_jual: 0, jenis_produk: '' },
        search: '',

        // --- LOGIC IMAGE PREVIEW ---
        imagePreview: null,

        // --- LOGIC AI SUGGESTION ---
        suggestingPrice: false,
        aiReason: '',
        aiError: '',

        // Fungsi Reset Form
        resetForm() {
            this.selectedProduk = {
                id: null,
                nama_produk: '',
                modal: 0,
                harga_jual: 0,
                jenis_produk: ''
            };
            this.imagePreview = null;
            this.aiReason = '';
            this.aiError = '';

            if (document.getElementById('fileInput')) {
                document.getElementById('fileInput').value = '';
            }
        },

        // Fungsi Tanya AI
        async suggestPrice() {
            this.aiError = '';
            this.aiReason = '';

            if (!this.selectedProduk.nama_produk || !this.selectedProduk.modal || !this.selectedProduk.jenis_produk) {
                this.aiError = 'Mohon isi Nama Produk, Modal, dan Jenis Produk terlebih dahulu.';
                return;
            }

            this.suggestingPrice = true;

            try {
                const response = await fetch('{{ route('produk.suggest-price') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nama_produk: this.selectedProduk.nama_produk,
                        modal: this.selectedProduk.modal,
                        jenis_produk: this.selectedProduk.jenis_produk
                    })
                });

                const data = await response.json();

                if (data.price) {
                    this.selectedProduk.harga_jual = data.price;
                    this.aiReason = data.reason;
                } else {
                    this.aiError = 'Gagal mendapatkan rekomendasi harga.';
                }
            } catch (error) {
                console.error(error);
                this.aiError = 'Terjadi kesalahan saat menghubungi AI.';
            } finally {
                this.suggestingPrice = false;
            }
        },

        // Format currency for display (adds Rp. and thousand separators)
        formatCurrency(value) {
            if (!value) return '';
            const number = parseFloat(value);
            if (isNaN(number)) return '';
            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(number);
        },

        // Parse currency input (removes Rp. and dots)
        parseCurrency(value) {
            if (!value) return '';
            return value.toString().replace(/[^0-9]/g, '');
        },

        // Handle modal input
        updateModal(event) {
            const rawValue = this.parseCurrency(event.target.value);
            this.selectedProduk.modal = rawValue;
            event.target.value = this.formatCurrency(rawValue);
        },

        // Handle harga jual input
        updateHargaJual(event) {
            const rawValue = this.parseCurrency(event.target.value);
            this.selectedProduk.harga_jual = rawValue;
            event.target.value = this.formatCurrency(rawValue);
        },

        // Get formatted display value for modal
        get displayModal() {
            return this.formatCurrency(this.selectedProduk.modal);
        },

        // Get formatted display value for harga jual
        get displayHargaJual() {
            return this.formatCurrency(this.selectedProduk.harga_jual);
        },

        // Fungsi Handle File Upload
        fileChosen(event) {
            const file = event.target.files[0];
            if (file) {
                this.imagePreview = URL.createObjectURL(file);
            }
        },

        openCreateModal() {
            this.resetForm();
            this.createModalOpen = true;
        },

        openEditModal(produk) {
            this.selectedProduk = JSON.parse(JSON.stringify(produk));
            if (produk.gambar) {
                this.imagePreview = '/storage/' + produk.gambar;
            } else {
                this.imagePreview = null;
            }
            this.editModalOpen = true;
        },

        openDeleteModal(produk) {
            this.selectedProduk = produk;
            this.deleteModalOpen = true;
        },

        closeModals() {
            this.createModalOpen = false;
            this.editModalOpen = false;
            this.deleteModalOpen = false;
            this.resetForm();
        }
    }">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Katalog Produk</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Kelola produk dan lihat penjualan bulanan</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <!-- Month Filter -->
                    <form method="GET" action="{{ route('produk.index') }}"
                        class="w-full sm:w-auto flex flex-col sm:flex-row gap-2">

                        <select name="month" onchange="this.form.submit()"
                            class="w-full sm:w-auto px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm text-gray-900 dark:text-gray-100">
                            @php
                                // Ambil bulan dari request, atau default ke bulan ini
                                $currentMonth = request('month', now()->format('m'));
                            @endphp

                            @foreach (range(1, 12) as $m)
                                @php
                                    $monthValue = sprintf('%02d', $m); // Format jadi 01, 02, dst.
                                @endphp
                                <option value="{{ $monthValue }}" {{ $currentMonth == $monthValue ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>

                        <select name="year" onchange="this.form.submit()"
                            class="w-full sm:w-auto px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm text-gray-900 dark:text-gray-100">
                            @php
                                // Ambil tahun dari request, atau default ke tahun ini
                                $currentYear = request('year', now()->format('Y'));
                                $startYear = 2020;
                                $thisYear = now()->year;
                            @endphp

                            @foreach (range($thisYear, $startYear) as $y)
                                <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>

                    </form>

                    <div class="relative group w-full sm:w-48">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" x-model="search" placeholder="Cari produk..."
                            class="pl-9 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 w-full transition-all shadow-sm text-gray-900 dark:text-gray-100">
                    </div>

                    <button @click="openCreateModal()"
                        class="inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm hover:shadow transition-all active:scale-95 text-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>
            </div>

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="mb-8 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg flex items-center gap-3 shadow-sm"
                    role="alert">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 pb-20">
                @forelse ($produks as $produk)
                    @php
                        $profit = $produk->harga_jual - $produk->modal;
                        $margin = $produk->harga_jual > 0 ? round(($profit / $produk->harga_jual) * 100) : 0;
                    @endphp

                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group"
                        x-show="search === '' || '{{ strtolower($produk->nama_produk) }}'.includes(search.toLowerCase())">

                        <!-- Product Image -->
                        <div class="aspect-[4/3] w-full bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                            @if ($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div
                                    class="w-full h-full flex flex-col items-center justify-center text-gray-300 dark:text-gray-600 bg-gray-200/50 dark:bg-gray-800">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Category Badge -->
                            <div class="absolute top-2 left-2">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-gray-700 dark:text-gray-300 shadow-sm">
                                    {{ $produk->jenis_produk }}
                                </span>
                            </div>

                            <!-- Action Buttons (Show on Hover) -->
                            <div
                                class="absolute top-2 right-2 flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button @click='openEditModal(@json($produk))'
                                    class="p-1.5 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm rounded text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-sm transition-colors"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button @click="openDeleteModal({{ $produk }})"
                                    class="p-1.5 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm rounded text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 shadow-sm transition-colors"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-3">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1 line-clamp-1"
                                title="{{ $produk->nama_produk }}">
                                {{ $produk->nama_produk }}
                            </h3>

                            <div class="flex items-baseline gap-1.5 mb-3">
                                <span class="text-base font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] text-gray-400 line-through">
                                    Rp {{ number_format($produk->modal, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Stats Grid -->
                            <div class="grid grid-cols-3 gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <div>
                                    <p class="text-[9px] text-gray-500 dark:text-gray-400 uppercase font-medium mb-0.5">
                                        Profit</p>
                                    <p class="text-xs font-semibold text-green-600 dark:text-green-400">
                                        +{{ number_format($profit, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-500 dark:text-gray-400 uppercase font-medium mb-0.5">
                                        Margin</p>
                                    <p class="text-xs font-semibold text-blue-600 dark:text-blue-400">
                                        {{ $margin }}%
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-500 dark:text-gray-400 uppercase font-medium mb-0.5">
                                        Terjual</p>
                                    <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                                        {{ $produk->total_terjual_bulan_ini ?? 0 }} unit
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div
                            class="mx-auto w-24 h-24 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Belum ada produk</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Mulai tambahkan produk untuk melihat
                            katalog Anda.</p>
                        <button @click="createModalOpen = true"
                            class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline text-sm">Tambah Produk
                            Baru</button>
                    </div>
                @endforelse
            </div>
        </div>

        <div x-show="createModalOpen || editModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
            x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="createModalOpen || editModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="closeModals()"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="createModalOpen || editModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative z-10 inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">

                    <form :action="editModalOpen ? '/produk/' + selectedProduk?.id : '{{ route('produk.store') }}'"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <template x-if="editModalOpen"><input type="hidden" name="_method" value="PUT"></template>

                        <div
                            class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white"
                                x-text="editModalOpen ? 'Edit Produk' : 'Tambah Produk Baru'"></h3>
                            <button type="button" @click="closeModals()"
                                class="text-gray-400 hover:text-gray-500 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 p-1">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-6 space-y-5 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto
                                    Produk</label>
                                <div class="flex items-center justify-center w-full">
                                    <label
                                        class="relative flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all overflow-hidden group">

                                        <div x-show="!imagePreview"
                                            class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Klik untuk
                                                upload gambar</p>
                                            <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 2MB</p>
                                        </div>

                                        <div x-show="imagePreview" class="absolute inset-0 w-full h-full"
                                            style="display: none;">
                                            <img :src="imagePreview" class="w-full h-full object-cover">
                                            <div
                                                class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <svg class="w-8 h-8 text-white mb-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                                    </path>
                                                </svg>
                                                <span class="text-white text-sm font-medium">Ganti Gambar</span>
                                            </div>
                                        </div>

                                        <input type="file" id="fileInput" name="gambar" class="hidden"
                                            accept="image/*" @change="fileChosen">
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama
                                    Produk</label>
                                <input type="text" name="nama_produk" x-model="selectedProduk.nama_produk" required
                                    placeholder="Contoh: Kopi Susu Aren"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm transition-shadow">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">HPP
                                        per unit</label>
                                    <input type="hidden" name="modal" x-model="selectedProduk.modal">
                                    <input type="text" @input="updateModal($event)" :value="displayModal" required
                                        placeholder="Rp. 0"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm transition-shadow">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 flex justify-between items-center">
                                        Harga Jual per unit
                                        <button type="button" @click="suggestPrice()" :disabled="suggestingPrice"
                                            class="text-xs bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 px-2 py-1 rounded-md hover:bg-indigo-200 dark:hover:bg-indigo-900/50 transition-colors flex items-center gap-1 disabled:opacity-50">
                                            <svg x-show="!suggestingPrice" class="w-3 h-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            <svg x-show="suggestingPrice" class="animate-spin w-3 h-3"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            <span x-text="suggestingPrice ? 'Menganalisa...' : 'Tanya AI'"></span>
                                        </button>
                                    </label>
                                    <input type="hidden" name="harga_jual" x-model="selectedProduk.harga_jual">
                                    <input type="text" @input="updateHargaJual($event)" :value="displayHargaJual"
                                        required placeholder="Rp. 0"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm transition-shadow">
                                    <p x-show="aiReason" x-text="aiReason"
                                        class="mt-1 text-xs text-indigo-600 dark:text-indigo-400 italic"></p>
                                    <p x-show="aiError" x-text="aiError"
                                        class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium animate-pulse"></p>
                                </div>
                            </div>



                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jenis
                                    Produk</label>
                                <input type="text" name="jenis_produk" x-model="selectedProduk.jenis_produk" required
                                    placeholder="Cth: Makanan"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm transition-shadow">
                            </div>
                        </div>

                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex flex-row-reverse gap-3 border-t border-gray-100 dark:border-gray-700">
                            <button type="submit"
                                class="inline-flex justify-center rounded-lg border border-transparent shadow-sm px-5 py-2.5 bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all active:scale-95">
                                <span x-text="editModalOpen ? 'Simpan Perubahan' : 'Simpan Produk'"></span>
                            </button>
                            <button type="button" @click="closeModals()"
                                class="inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-5 py-2.5 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="deleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="deleteModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
                    @click="deleteModalOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="deleteModalOpen" x-transition.scale
                    class="relative z-10 inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">

                    <div class="flex items-center gap-4">
                        <div
                            class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hapus Produk?</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Yakin ingin menghapus <span class="font-bold text-gray-800 dark:text-gray-200"
                                    x-text="selectedProduk?.nama_produk"></span>?
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-row-reverse gap-3">
                        <form :action="'/produk/' + selectedProduk?.id" method="POST" class="w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none transition-colors">
                                Ya, Hapus
                            </button>
                        </form>
                        <button @click="deleteModalOpen = false" type="button"
                            class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
