@extends('layouts.app')

@section('header', 'Manajemen Produk')

@section('content')
<div class="py-8 w-full" x-data="productManager()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Katalog Produk</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Kelola produk dan lihat penjualan bulanan</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <!-- Month/Year Filter -->
                <form method="GET" action="{{ route('produk.index') }}" class="flex gap-2">
                    <select name="month" onchange="this.form.submit()" class="px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 text-gray-900 dark:text-gray-100">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}" {{ request('month', now()->format('m')) == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" onchange="this.form.submit()" class="px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 text-gray-900 dark:text-gray-100">
                        @foreach(range(now()->year, 2020) as $y)
                            <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>

                <!-- Search -->
                <div class="relative">
                    <svg class="absolute left-3 top-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search" placeholder="Cari produk..." class="pl-9 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm w-48 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Add Button -->
                <button @click="openModal('create')" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah
                </button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 pb-20">
            @forelse($produks as $produk)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all group"
                     x-show="!search || '{{ strtolower($produk->nama_produk) }}'.includes(search.toLowerCase())">
                    
                    <!-- Image -->
                    <div class="aspect-[4/3] bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                        @if($produk->gambar)
                            <img src="{{ asset('storage/'.$produk->gambar) }}" alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Badge & Actions -->
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-medium bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-gray-700 dark:text-gray-300">
                            {{ $produk->jenis_produk }}
                        </span>
                        <div class="absolute top-2 right-2 flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click='openModal("edit", @json($produk))' class="p-1.5 bg-white/90 dark:bg-gray-900/90 rounded hover:text-indigo-600 dark:hover:text-indigo-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </button>
                            <button @click="openModal('delete', {{ $produk }})" class="p-1.5 bg-white/90 dark:bg-gray-900/90 rounded hover:text-red-600 dark:hover:text-red-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1 truncate">{{ $produk->nama_produk }}</h3>
                        <div class="flex items-baseline gap-1.5 mb-3">
                            <span class="text-base font-bold text-gray-900 dark:text-white">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                            <span class="text-[10px] text-gray-400 line-through">Rp {{ number_format($produk->modal, 0, ',', '.') }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <div>
                                <p class="text-[9px] text-gray-500 dark:text-gray-400 uppercase mb-0.5">Profit</p>
                                <p class="text-xs font-semibold text-green-600 dark:text-green-400">+{{ number_format($produk->harga_jual - $produk->modal, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-500 dark:text-gray-400 uppercase mb-0.5">Margin</p>
                                <p class="text-xs font-semibold text-blue-600 dark:text-blue-400">{{ $produk->harga_jual > 0 ? round((($produk->harga_jual - $produk->modal) / $produk->harga_jual) * 100) : 0 }}%</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-500 dark:text-gray-400 uppercase mb-0.5">Terjual</p>
                                <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">{{ $produk->total_terjual_bulan_ini ?? 0 }} unit</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Belum ada produk</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Mulai tambahkan produk untuk melihat katalog Anda.</p>
                    <button @click="openModal('create')" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline text-sm">Tambah Produk Baru</button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="modal !== null" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="modal !== null" @click="closeModal()" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            
            <div x-show="modal === 'create' || modal === 'edit'" x-transition class="relative z-10 inline-block w-full max-w-lg my-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                <form :action="modal === 'edit' ? '/produk/' + form.id : '{{ route('produk.store') }}'" method="POST" enctype="multipart/form-data">
                    @csrf
                    <template x-if="modal === 'edit'"><input type="hidden" name="_method" value="PUT"></template>

                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="modal === 'edit' ? 'Edit Produk' : 'Tambah Produk Baru'"></h3>
                        <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-500 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 p-1">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6 space-y-5 max-h-[70vh] overflow-y-auto">
                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Produk</label>
                            <label class="relative flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 overflow-hidden group">
                                <div x-show="!imagePreview" class="flex flex-col items-center justify-center">
                                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Klik untuk upload gambar</p>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 2MB</p>
                                </div>
                                <img x-show="imagePreview" :src="imagePreview" class="absolute inset-0 w-full h-full object-cover">
                                <input type="file" name="gambar" class="hidden" accept="image/*" @change="handleFile($event)">
                            </label>
                        </div>

                        <!-- Product Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Produk</label>
                            <input type="text" name="nama_produk" x-model="form.nama_produk" required placeholder="Contoh: Kopi Susu Aren" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 dark:bg-gray-700 dark:text-white text-sm">
                        </div>

                        <!-- Price Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">HPP per unit</label>
                                <input type="hidden" name="modal" x-model="form.modal">
                                <input type="text" @input="updatePrice('modal', $event)" :value="formatRp(form.modal)" required placeholder="Rp. 0" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 dark:bg-gray-700 dark:text-white text-sm">
                            </div>
                            <div>
                                <label class="flex justify-between items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Harga Jual per unit
                                    <button type="button" @click="suggestPrice()" :disabled="suggesting" class="text-xs bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 px-2 py-1 rounded-md hover:bg-indigo-200 dark:hover:bg-indigo-900/50 disabled:opacity-50">
                                        <span x-text="suggesting ? 'Menganalisa...' : 'Tanya AI'"></span>
                                    </button>
                                </label>
                                <input type="hidden" name="harga_jual" x-model="form.harga_jual">
                                <input type="text" @input="updatePrice('harga_jual', $event)" :value="formatRp(form.harga_jual)" required placeholder="Rp. 0" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 dark:bg-gray-700 dark:text-white text-sm">
                                <p x-show="aiReason" x-text="aiReason" class="mt-1 text-xs text-indigo-600 dark:text-indigo-400 italic"></p>
                                <p x-show="aiError" x-text="aiError" class="mt-1 text-xs text-red-600 dark:text-red-400 animate-pulse"></p>
                            </div>
                        </div>

                        <!-- Product Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jenis Produk</label>
                            <input type="text" name="jenis_produk" x-model="form.jenis_produk" required placeholder="Cth: Makanan" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 dark:bg-gray-700 dark:text-white text-sm">
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex flex-row-reverse gap-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-sm font-medium text-white rounded-lg hover:bg-indigo-700 transition-all">
                            <span x-text="modal === 'edit' ? 'Simpan Perubahan' : 'Simpan Produk'"></span>
                        </button>
                        <button type="button" @click="closeModal()" class="px-5 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="modal === 'delete'" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="modal === 'delete'" @click="closeModal()" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            <div x-show="modal === 'delete'" x-transition.scale class="relative z-10 inline-block w-full max-w-md p-6 my-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hapus Produk?</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Yakin ingin menghapus <span class="font-bold text-gray-800 dark:text-gray-200" x-text="form.nama_produk"></span>?
                        </p>
                    </div>
                </div>
                <div class="mt-6 flex flex-row-reverse gap-3">
                    <form :action="'/produk/' + form.id" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-sm font-medium text-white rounded-lg hover:bg-red-700">Ya, Hapus</button>
                    </form>
                    <button @click="closeModal()" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function productManager() {
    return {
        modal: {{ $errors->any() && !session('success') ? "'create'" : 'null' }},
        form: { id: null, nama_produk: '', modal: 0, harga_jual: 0, jenis_produk: '' },
        search: '',
        imagePreview: null,
        suggesting: false,
        aiReason: '',
        aiError: '',

        openModal(type, data = null) {
            this.modal = type;
            if (type === 'edit' || type === 'delete') {
                this.form = JSON.parse(JSON.stringify(data));
                this.imagePreview = data.gambar ? '/storage/' + data.gambar : null;
            } else {
                this.resetForm();
            }
        },

        closeModal() {
            this.modal = null;
            this.resetForm();
        },

        resetForm() {
            this.form = { id: null, nama_produk: '', modal: 0, harga_jual: 0, jenis_produk: '' };
            this.imagePreview = null;
            this.aiReason = '';
            this.aiError = '';
        },

        async suggestPrice() {
            if (!this.form.nama_produk || !this.form.modal || !this.form.jenis_produk) {
                this.aiError = 'Mohon isi Nama Produk, Modal, dan Jenis Produk terlebih dahulu.';
                return;
            }

            this.suggesting = true;
            this.aiError = '';
            this.aiReason = '';

            try {
                const response = await fetch('{{ route('produk.suggest-price') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ nama_produk: this.form.nama_produk, modal: this.form.modal, jenis_produk: this.form.jenis_produk })
                });
                const data = await response.json();
                
                if (data.price) {
                    this.form.harga_jual = data.price;
                    this.aiReason = data.reason;
                } else {
                    this.aiError = 'Gagal mendapatkan rekomendasi harga.';
                }
            } catch (error) {
                this.aiError = 'Terjadi kesalahan saat menghubungi AI.';
            } finally {
                this.suggesting = false;
            }
        },

        formatRp(value) {
            if (!value) return '';
            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(value);
        },

        updatePrice(field, event) {
            const value = event.target.value.replace(/[^0-9]/g, '');
            this.form[field] = value;
            event.target.value = this.formatRp(value);
        },

        handleFile(event) {
            const file = event.target.files[0];
            if (file) this.imagePreview = URL.createObjectURL(file);
        }
    }
}

// Toast notifications
document.addEventListener('DOMContentLoaded', () => {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    const isDark = document.documentElement.classList.contains('dark');
    const colors = isDark ? { background: '#1f2937', color: '#f3f4f6' } : { background: '#ffffff', color: '#1f2937' };

    @if(session('success'))
        Toast.fire({ icon: 'success', title: "{{ session('success') }}", ...colors });
    @endif

    @if(session('error'))
        Toast.fire({ icon: 'error', title: "{{ session('error') }}", ...colors });
    @endif
});
</script>
@endsection