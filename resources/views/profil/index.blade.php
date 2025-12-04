@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/50 dark:bg-gray-950 py-8 transition-colors duration-300" 
    x-data="{
        isEditing: false,
        categorySearch: '',
        showCategoryDropdown: false,
        categories: {{ json_encode($categories->pluck('name')->toArray()) }},
        selectedCategory: '{{ old('kategori', optional(optional($business)->category)->name ?? '') }}',

        imagePreview: '{{ optional($business)->gambar ? Storage::url($business->gambar) : '' }}',
        originalImage: '{{ optional($business)->gambar ? Storage::url($business->gambar) : '' }}',
        deleteImage: false,
        
        // Modal Preview Image
        showImageModal: false,
        modalImageUrl: '',

        openImageModal(url) {
            this.modalImageUrl = url;
            this.showImageModal = true;
        },

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
                // Cancel Edit
                this.isEditing = false;
                this.imagePreview = this.originalImage;
                this.deleteImage = false;
                this.selectedCategory = '{{ optional(optional($business)->category)->name ?? '' }}';
                if(document.getElementById('gambar-input')) document.getElementById('gambar-input').value = '';
            } else {
                // Start Edit
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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 1. HEADER SECTION & ACTIONS --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Profil Bisnis</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Identitas dan informasi publik bisnis Anda di Amerta.</p>
                </div>

                <div class="flex items-center gap-3">
                    <button @click="toggleEdit()" type="button"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 shadow-sm border"
                        :class="isEditing 
                            ? 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' 
                            : 'bg-indigo-600 text-white border-transparent hover:bg-indigo-700 hover:shadow-indigo-500/30'">
                        <svg x-show="!isEditing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        <svg x-show="isEditing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        <span x-text="isEditing ? 'Batal Edit' : 'Edit Profil'"></span>
                    </button>
                </div>
            </div>

            @if (session('success'))
               <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="mb-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
                    <div class="p-1 bg-emerald-100 dark:bg-emerald-800 rounded-full"><svg class="w-4 h-4 text-emerald-600 dark:text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></div>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('profil_bisnis.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="hapus_gambar" :value="deleteImage ? '1' : '0'">

                {{-- 2. MAIN IDENTITY CARD --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 p-6 mb-6 relative overflow-visible">
                    {{-- Decorative Background --}}
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 dark:bg-indigo-900/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-6">
                        {{-- Image Upload Section --}}
                        <div class="group relative shrink-0">
                            <input type="file" id="gambar-input" name="gambar" accept="image/*" class="hidden" @change="updatePreview($event)">
                            
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden border-4 border-white dark:border-gray-700 shadow-md bg-gray-100 dark:bg-gray-800 relative"
                                 :class="{ 'cursor-pointer ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-gray-800': isEditing }">
                                
                                {{-- Image Logic --}}
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                         @click="!isEditing && openImageModal(imagePreview)" :class="{'cursor-zoom-in': !isEditing}">
                                </template>
                                <template x-if="!imagePreview">
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500" @click="isEditing && document.getElementById('gambar-input').click()">
                                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span class="text-[10px] font-medium">Logo</span>
                                    </div>
                                </template>

                                {{-- Edit Overlay --}}
                                <div x-show="isEditing" 
                                     @click="document.getElementById('gambar-input').click()"
                                     class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer backdrop-blur-[1px]">
                                    <svg class="w-6 h-6 text-white mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /></svg>
                                    <span class="text-xs text-white font-medium">Ubah Foto</span>
                                </div>
                            </div>

                            {{-- Remove Button --}}
                            <button type="button" x-show="isEditing && imagePreview" @click="removeImage()"
                                class="absolute -top-2 -right-2 bg-white dark:bg-gray-700 text-red-500 rounded-full p-1.5 shadow-md border border-gray-100 dark:border-gray-600 hover:bg-red-50 dark:hover:bg-gray-600 transition-colors z-20">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        {{-- Name & Category Inputs --}}
                        <div class="flex-1 w-full space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama Bisnis</label>
                                <template x-if="!isEditing">
                                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white leading-tight">
                                        {{ optional($business)->nama_bisnis ?? 'Belum ada nama' }}
                                    </h2>
                                </template>
                                <template x-if="isEditing">
                                    <input type="text" name="nama_bisnis"
                                        value="{{ old('nama_bisnis', optional($business)->nama_bisnis ?? '') }}"
                                        placeholder="Contoh: Kopi Kenangan"
                                        class="w-full text-xl font-bold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 px-3 py-2 placeholder-gray-400 transition-all">
                                </template>
                                @error('nama_bisnis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="relative max-w-sm">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kategori</label>
                                <template x-if="!isEditing">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-sm font-medium border border-indigo-100 dark:border-indigo-800">
                                        {{ optional(optional($business)->category)->name ?? 'Belum ada kategori' }}
                                    </span>
                                </template>
                                <template x-if="isEditing">
                                    <div class="relative z-50">
                                        <input type="hidden" name="kategori" :value="selectedCategory">
                                        <div class="relative">
                                            <input type="text" x-model="categorySearch"
                                                @focus="showCategoryDropdown = true"
                                                @click.away="showCategoryDropdown = false"
                                                :placeholder="selectedCategory || 'Cari Kategori...'"
                                                class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm text-gray-900 dark:text-white transition-all">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                            </div>
                                        </div>

                                        {{-- Dropdown --}}
                                        <div x-show="showCategoryDropdown && (filteredCategories.length > 0 || showAddButton)"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            class="absolute z-[100] w-full mt-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl max-h-56 overflow-y-auto custom-scrollbar">
                                            <template x-for="category in filteredCategories" :key="category">
                                                <button type="button" @click="selectCategory(category)"
                                                    class="w-full text-left px-4 py-2.5 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-sm text-gray-700 dark:text-gray-200 transition-colors"
                                                    x-text="category">
                                                </button>
                                            </template>
                                            <template x-if="showAddButton">
                                                <button type="button" @click="addNewCategory()"
                                                    class="w-full text-left px-4 py-2.5 bg-indigo-50 dark:bg-indigo-900/10 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 text-sm text-indigo-600 dark:text-indigo-400 font-medium border-t border-gray-100 dark:border-gray-700 flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                    <span>Tambah "<span x-text="categorySearch"></span>"</span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. GRID DETAILS --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- LEFT COL: General Info --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Card: Tentang Bisnis --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 h-full">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Tentang Bisnis
                            </h3>
                            
                            <div class="space-y-6">
                                {{-- Tujuan --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Tujuan Utama</label>
                                    <template x-if="!isEditing">
                                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                            {{ $business?->tujuan_utama ?: 'Belum diisi' }}
                                        </p>
                                    </template>
                                    <template x-if="isEditing">
                                        <textarea name="tujuan_utama" rows="3" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-gray-900 dark:text-white text-sm px-3 py-2.5 placeholder-gray-400" placeholder="Contoh: Menjadi kedai kopi nomor 1 di Jakarta Selatan">{{ old('tujuan_utama', $business?->tujuan_utama) }}</textarea>
                                    </template>
                                </div>

                                {{-- Alamat --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                                    <template x-if="!isEditing">
                                        <div class="flex items-start gap-3 bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                            <svg class="w-5 h-5 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $business?->alamat ?: 'Belum diisi' }}</p>
                                        </div>
                                    </template>
                                    <template x-if="isEditing">
                                        <textarea name="alamat" rows="3" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-gray-900 dark:text-white text-sm px-3 py-2.5 placeholder-gray-400" placeholder="Jalan Sudirman No. 1...">{{ old('alamat', $business?->alamat) }}</textarea>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COL: Stats & Meta --}}
                    <div class="lg:col-span-1 space-y-6">
                        
                        {{-- Invite Code Card --}}
                        <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                            
                            <h4 class="text-sm font-semibold text-gray-950 dark:text-indigo-100 uppercase tracking-wide mb-4">Kode Tim</h4>
                            <div class="flex items-center gap-2" x-data="{ copied: false }">
                                <code class="flex-1 bg-white/20 backdrop-blur-sm text-gray-900 dark:text-white border border-white/20 px-4 py-3 rounded-xl text-lg font-mono font-bold tracking-widest text-center shadow-inner">
                                    {{ optional($business)->invite_code ?? '---' }}
                                </code>
                                <button type="button" @click="navigator.clipboard.writeText('{{ optional($business)->invite_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="p-3 bg-white text-indigo-600 rounded-xl hover:bg-indigo-50 transition-colors shadow-lg active:scale-95">
                                    <svg x-show="!copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    <svg x-show="copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-900 dark:text-indigo-200 mt-3 leading-relaxed">Bagikan kode ini kepada staff Anda untuk bergabung ke dalam workspace ini.</p>
                        </div>

                        {{-- Details Card --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Detail Lainnya</h4>
                            <div class="space-y-4">
                                {{-- Target Pasar --}}
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Target Pasar</label>
                                    <template x-if="!isEditing">
                                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ optional($business)->target_pasar ?? '-' }}</p>
                                    </template>
                                    <template x-if="isEditing">
                                        <input type="text" name="target_pasar" value="{{ old('target_pasar', $business?->target_pasar) }}" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 px-3 py-2.5 placeholder-gray-400" placeholder="Cth: Remaja">
                                    </template>
                                </div>
                                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                {{-- Jumlah Tim --}}
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Jumlah Tim</label>
                                    <template x-if="!isEditing">
                                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ optional($business)->jumlah_tim ?? '0' }} Orang</p>
                                    </template>
                                    <template x-if="isEditing">
                                        <input type="number" name="jumlah_tim" value="{{ old('jumlah_tim', $business?->jumlah_tim) }}" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 px-3 py-2.5 placeholder-gray-400" placeholder="0">
                                    </template>
                                </div>
                                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                {{-- Telepon --}}
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Telepon / WA</label>
                                    <template x-if="!isEditing">
                                        <p class="font-medium text-gray-800 dark:text-gray-200 font-mono">{{ optional($business)->telepon ?? '-' }}</p>
                                    </template>
                                    <template x-if="isEditing">
                                        <input type="text" name="telepon" value="{{ old('telepon', $business?->telepon) }}" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 px-3 py-2.5 placeholder-gray-400" placeholder="0812...">
                                    </template>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- SAVE BAR (Floating) --}}
                <div x-show="isEditing" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10" x-cloak
                    class="fixed bottom-6 right-6 z-40 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-2xl rounded-2xl px-6 py-4 flex items-center gap-4 max-w-2xl">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Perubahan belum disimpan</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pastikan data sudah benar sebelum menyimpan.</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" @click="toggleEdit()" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Batal</button>
                        <button type="submit" class="px-6 py-2 rounded-lg text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all active:scale-95">Simpan</button>
                    </div>
                </div>

            </form>
        </div>

        {{-- IMAGE MODAL --}}
        <div x-show="showImageModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4"
            x-transition.opacity @click.self="showImageModal = false" @keydown.escape.window="showImageModal = false">
            <div class="relative max-w-5xl w-full flex flex-col items-center">
                <button @click="showImageModal = false" class="absolute -top-12 right-0 text-white/70 hover:text-white p-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <img :src="modalImageUrl" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl ring-1 ring-white/10">
            </div>
        </div>

    </div>
@endsection