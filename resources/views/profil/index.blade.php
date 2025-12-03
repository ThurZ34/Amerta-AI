@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8" x-data="{
        isEditing: false,
        categorySearch: '',
        showCategoryDropdown: false,
        categories: {{ json_encode($categories->pluck('name')->toArray()) }},
        selectedCategory: '{{ old('kategori', optional(optional($business)->category)->name ?? '') }}',

        imagePreview: '{{ optional($business)->gambar ? Storage::url($business->gambar) : '' }}',
        originalImage: '{{ optional($business)->gambar ? Storage::url($business->gambar) : '' }}',
        deleteImage: false,

        updatePreview(event) {
            const file = event.target.files[0];
            if (file) {
                this.deleteImage = false;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        removeImage() {
            this.imagePreview = '';
            this.deleteImage = true;
            document.getElementById('gambar-input').value = '';
        },

        toggleEdit() {
            if (this.isEditing) {
                this.isEditing = false;
                this.imagePreview = this.originalImage;
                this.deleteImage = false;
                document.getElementById('gambar-input').value = '';
            } else {
                // Masuk mode edit
                this.isEditing = true;
            }
        },

        get filteredCategories() {
            if (!this.categorySearch) return this.categories;
            return this.categories.filter(cat =>
                cat.toLowerCase().includes(this.categorySearch.toLowerCase())
            );
        },

        get showAddButton() {
            return this.categorySearch &&
                !this.categories.some(cat => cat.toLowerCase() === this.categorySearch.toLowerCase());
        },

        selectCategory(category) {
            this.selectedCategory = category;
            this.categorySearch = '';
            this.showCategoryDropdown = false;
        },

        async addNewCategory() {
             try {
                const response = await fetch('{{ route('categories.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: this.categorySearch })
                });

                const data = await response.json();
                if (data.success) {
                    this.categories.push(data.category.name);
                    this.selectCategory(data.category.name);
                }
            } catch (error) {
                console.error('Error adding category:', error);
            }
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Profil Bisnis</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Kelola informasi dan detail bisnis Anda.</p>
                </div>

                <button @click="toggleEdit()" type="button"
                    class="inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-sm hover:shadow transition-all active:scale-95 text-sm whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span x-text="isEditing ? 'Batal Edit' : 'Edit Profil'"></span>
                </button>
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

            <form action="{{ route('profil_bisnis.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="hapus_gambar" :value="deleteImage ? '1' : '0'">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

                        <div class="px-6 py-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-4">
                                <input type="file" id="gambar-input" name="gambar" accept="image/*" class="hidden"
                                    @change="updatePreview($event)">

                                <div class="relative w-14 h-14 flex-shrink-0">
                                    <div class="w-full h-full bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center overflow-hidden group border border-gray-200 dark:border-gray-600"
                                        :class="{ 'cursor-pointer': isEditing }"
                                        @click="isEditing && document.getElementById('gambar-input').click()">

                                        <template x-if="imagePreview">
                                            <img :src="imagePreview" alt="Business Logo" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!imagePreview">
                                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </template>

                                        <div x-show="isEditing" x-cloak
                                            class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <button type="button"
                                        x-show="isEditing && imagePreview" x-cloak
                                        @click="removeImage()"
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition-colors z-10"
                                        title="Hapus Gambar">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex-1">
                                    <template x-if="!isEditing">
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                {{ optional($business)->nama_bisnis ?? 'Nama Bisnis Belum Diisi' }}</h3>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-0.5">
                                                {{ optional(optional($business)->category)->name ?? 'Kategori belum diisi' }}</p>
                                        </div>
                                    </template>
                                    <template x-if="isEditing">
                                        <div class="space-y-2">
                                            <input type="text" name="nama_bisnis"
                                                value="{{ old('nama_bisnis', optional($business)->nama_bisnis ?? '') }}"
                                                placeholder="Nama Bisnis"
                                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-lg font-semibold">
                                            @error('nama_bisnis')
                                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z" />
                                    </svg>
                                    Tujuan Utama
                                </label>
                                <template x-if="!isEditing">
                                    <p
                                        class="text-gray-900 dark:text-white px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 rounded-lg min-h-[80px]">
                                        {{ $business?->tujuan_utama ?? '-' }}
                                    </p>
                                </template>
                                <template x-if="isEditing">
                                    <textarea name="tujuan_utama" rows="3" placeholder="Apa tujuan utama bisnis Anda?"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm transition-shadow">{{ old('tujuan_utama', $business?->tujuan_utama ?? '') }}</textarea>
                                </template>
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Alamat
                                </label>
                                <template x-if="!isEditing">
                                    <p
                                        class="text-gray-900 dark:text-white px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 rounded-lg min-h-[80px]">
                                        {{ $business?->alamat ?? '-' }}
                                    </p>
                                </template>
                                <template x-if="isEditing">
                                    <textarea name="alamat" rows="3" placeholder="Alamat lengkap bisnis Anda"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm transition-shadow">{{ old('alamat', $business?->alamat ?? '') }}</textarea>
                                </template>
                            </div>
                        </div>

                        <div x-show="isEditing" x-transition x-cloak
                            class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex flex-row-reverse gap-3 border-t border-gray-100 dark:border-gray-700">
                            <button type="submit"
                                class="inline-flex justify-center rounded-lg border border-transparent shadow-sm px-5 py-2.5 bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all active:scale-95">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan
                            </button>
                            <button type="button" @click="toggleEdit()"
                                class="inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-5 py-2.5 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition-colors">
                                Batal
                            </button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Informasi Cepat</h4>
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label
                                        class="flex items-center gap-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        Kode Invite Tim
                                    </label>
                                    <div class="flex items-center gap-2" x-data="{ copied: false }">
                                        <code
                                            class="flex-1 bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded-lg text-sm font-mono font-bold text-gray-900 dark:text-white tracking-wider text-center border border-gray-200 dark:border-gray-600">
                                            {{ optional($business)->invite_code ?? 'BELUM ADA' }}
                                        </code>
                                        <button
                                            @click="navigator.clipboard.writeText('{{ optional($business)->invite_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                            class="p-2 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors relative group">
                                            <svg x-show="!copied" class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <svg x-show="copied" class="w-5 h-5 text-green-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>

                                            <span x-show="copied" x-transition x-cloak
                                                class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                                Disalin!
                                            </span>
                                        </button>
                                    </div>
                                    <p class="text-[10px] text-gray-400 leading-tight">
                                        Bagikan kode ini ke karyawan Anda untuk bergabung ke tim.
                                    </p>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                <div class="space-y-2" x-data="{ open: false }">
                                    <label
                                        class="flex items-center gap-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Kategori
                                    </label>
                                    <template x-if="!isEditing">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ optional(optional($business)->category)->name ?? '-' }}
                                        </p>
                                    </template>
                                    <template x-if="isEditing">
                                        <div class="relative">
                                            <input type="hidden" name="kategori" :value="selectedCategory">
                                            <input type="text" x-model="categorySearch"
                                                @focus="showCategoryDropdown = true"
                                                @click.away="showCategoryDropdown = false"
                                                :placeholder="selectedCategory || 'Cari atau tambah kategori...'"
                                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">

                                            <div x-show="showCategoryDropdown && (filteredCategories.length > 0 || showAddButton)"
                                                x-transition x-cloak
                                                class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">

                                                <template x-for="category in filteredCategories" :key="category">
                                                    <button type="button" @click="selectCategory(category)"
                                                        class="w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 text-sm text-gray-900 dark:text-white"
                                                        x-text="category">
                                                    </button>
                                                </template>

                                                <template x-if="showAddButton">
                                                    <button type="button" @click="addNewCategory()"
                                                        class="w-full text-left px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 text-sm text-indigo-600 dark:text-indigo-400 border-t border-gray-200 dark:border-gray-600 flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        <span>Tambah "<span x-text="categorySearch"></span>"</span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700"></div>

                                <div class="space-y-2">
                                    <label
                                        class="flex items-center gap-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Target Pasar
                                    </label>
                                    <template x-if="!isEditing">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ optional($business)->target_pasar ?? '-' }}
                                        </p>
                                    </template>
                                    <template x-if="isEditing">
                                        <input type="text" name="target_pasar"
                                            value="{{ old('target_pasar', optional($business)->target_pasar ?? '') }}"
                                            placeholder="Contoh: Mahasiswa"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                                    </template>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700"></div>

                                <div class="space-y-2">
                                    <label
                                        class="flex items-center gap-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        Jumlah Tim
                                    </label>
                                    <template x-if="!isEditing">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ optional($business)->jumlah_tim ?? '0' }} Orang
                                        </p>
                                    </template>
                                    <template x-if="isEditing">
                                        <input type="number" name="jumlah_tim"
                                            value="{{ old('jumlah_tim', optional($business)->jumlah_tim ?? '') }}" placeholder="0"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                                    </template>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700"></div>

                                <div class="space-y-2">
                                    <label
                                        class="flex items-center gap-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        Telepon
                                    </label>
                                    <template x-if="!isEditing">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ optional($business)->telepon ?? '-' }}
                                        </p>
                                    </template>
                                    <template x-if="isEditing">
                                        <input type="text" name="telepon"
                                            value="{{ old('telepon', optional($business)->telepon ?? '') }}"
                                            placeholder="08123456789"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div x-show="showImageModal"
            style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-90 backdrop-blur-sm p-4"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.self="showImageModal = false"
            @keydown.escape.window="showImageModal = false">

            <div class="relative max-w-4xl w-full max-h-screen flex flex-col items-center">
                <button @click="showImageModal = false"
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 focus:outline-none p-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <img :src="modalImageUrl"
                    class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
                    alt="Full size business image">
            </div>
        </div>
    </div>
@endsection
