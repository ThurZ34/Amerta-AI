@extends('layouts.app')

@section('header', 'Riwayat Keuangan')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8" x-data="financeApp({
        riwayats: {{ Js::from($riwayats) }},
        scanResult: {{ Js::from(session('scan_result')) }},
        categories: {{ Js::from($categories ?? []) }}
    })">

        <div class="max-w-4xl mx-auto space-y-8">

            <form id="scanForm" action="{{ route('riwayat.scan') }}" method="POST" enctype="multipart/form-data"
                class="hidden">
                @csrf
                <input type="file" id="scanInput" name="receipt_image" accept="image/*" @change="submitScan()">
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:border-emerald-200 dark:hover:border-emerald-900 transition-colors">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Pemasukan</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">
                                Rp <span x-text="formatRupiah(totalPendapatan)"></span>
                            </h3>
                        </div>
                        <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 11l5-5m0 0l5 5m-5-5v12" />
                            </svg>
                        </div>
                    </div>
                    <div
                        class="absolute bottom-0 left-0 h-1 w-full bg-emerald-500 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left">
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:border-rose-200 dark:hover:border-rose-900 transition-colors">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Pengeluaran</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">
                                Rp <span x-text="formatRupiah(totalPengeluaran)"></span>
                            </h3>
                        </div>
                        <div class="p-3 bg-rose-100 dark:bg-rose-900/30 text-rose-600 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                            </svg>
                        </div>
                    </div>
                    <div
                        class="absolute bottom-0 left-0 h-1 w-full bg-rose-500 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left">
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <form action="{{ route('riwayat.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <select name="month" onchange="this.form.submit()"
                        class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" onchange="this.form.submit()"
                        class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500">
                        @foreach (range(date('Y'), date('Y') - 5) as $y)
                            <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <div class="flex gap-2 w-full sm:w-auto">
                    <button @click="triggerScan()"
                        class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 rounded-xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Scan
                    </button>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Catat
                        </button>
                        <div x-show="open" x-transition.origin.top.right
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden"
                            style="display: none;">
                            <button @click="openAddModal('pengeluaran'); open = false"
                                class="w-full text-left px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-rose-500"></div> Pengeluaran
                            </button>
                            <button @click="openAddModal('pendapatan'); open = false"
                                class="w-full text-left px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2 border-t border-gray-100 dark:border-gray-700">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div> Pendapatan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

                <div
                    class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-gray-900 dark:text-white">Riwayat Transaksi</h3>

                    <div class="flex p-1 bg-gray-200 dark:bg-gray-700 rounded-lg">
                        <button @click="filterType = 'all'; currentPage = 1"
                            class="px-4 py-1.5 text-xs font-semibold rounded-md transition-all"
                            :class="filterType === 'all' ?
                                'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' :
                                'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'">
                            Semua
                        </button>
                        <button @click="filterType = 'pendapatan'; currentPage = 1"
                            class="px-4 py-1.5 text-xs font-semibold rounded-md transition-all"
                            :class="filterType === 'pendapatan' ?
                                'bg-white dark:bg-gray-600 text-emerald-600 dark:text-emerald-400 shadow-sm' :
                                'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'">
                            Masuk
                        </button>
                        <button @click="filterType = 'pengeluaran'; currentPage = 1"
                            class="px-4 py-1.5 text-xs font-semibold rounded-md transition-all"
                            :class="filterType === 'pengeluaran' ?
                                'bg-white dark:bg-gray-600 text-rose-600 dark:text-rose-400 shadow-sm' :
                                'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'">
                            Keluar
                        </button>
                    </div>
                </div>

                <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                    <template x-for="riwayat in paginatedRiwayats" :key="riwayat.id">
                        <li class="p-4 transition-colors group"
                            :class="{ 'hover:bg-gray-50 dark:hover:bg-gray-700/50': true }">

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                                        :class="riwayat.jenis === 'pengeluaran' ?
                                            'bg-rose-100 text-rose-600 dark:bg-rose-900/30' :
                                            'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30'">
                                        <svg x-show="riwayat.jenis === 'pengeluaran'" class="w-5 h-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <svg x-show="riwayat.jenis === 'pendapatan'" class="w-5 h-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-[150px] sm:max-w-xs"
                                            x-text="riwayat.nama_barang"></h4>
                                        <div
                                            class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            <span x-text="formatDate(riwayat.tanggal_pembelian)"></span>
                                            <span>•</span>
                                            <span x-text="riwayat.metode_pembayaran"></span>
                                            <template x-if="riwayat.kategori">
                                                <span
                                                    class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-[10px]"
                                                    x-text="riwayat.kategori"></span>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="text-sm font-bold"
                                        :class="riwayat.jenis === 'pengeluaran' ? 'text-rose-600' : 'text-emerald-600'"
                                        x-text="(riwayat.jenis === 'pengeluaran' ? '- ' : '+ ') + 'Rp ' + formatRupiah(riwayat.total_harga)">
                                    </p>

                                    <div class="flex items-center justify-end gap-3 mt-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                        x-show="riwayat.is_manual !== false">
                                        <button @click="openEditModal(riwayat)"
                                            class="text-xs text-gray-400 hover:text-indigo-500 transition-colors"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>

                                        <form :id="'delete-form-' + riwayat.id"
                                            :action="`{{ route('riwayat.index') }}/${riwayat.id}`" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button" @click="confirmDelete(riwayat.id)"
                                                class="text-xs text-gray-400 hover:text-red-500 transition-colors"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <div x-show="riwayat.is_manual === false" class="mt-1">
                                        <span
                                            class="text-[10px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded border border-indigo-100">Otomatis</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </template>

                    <li x-show="paginatedRiwayats.length === 0" class="p-8 text-center" x-cloak>
                        <p class="text-gray-500 text-sm">Tidak ada data untuk ditampilkan.</p>
                    </li>
                </ul>

                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 flex items-center justify-between"
                    x-show="filteredRiwayats.length > itemsPerPage">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        Hal <span x-text="currentPage"></span> dari <span x-text="totalPages"></span>
                    </span>
                    <div class="flex gap-2">
                        <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                            class="px-3 py-1 text-xs font-medium rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                            Prev
                        </button>
                        <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                            class="px-3 py-1 text-xs font-medium rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                            Next
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
                <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="showModal = false">
                </div>
                <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transform transition-all"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95">

                    <form :action="isEditing ? `{{ route('riwayat.index') }}/${editId}` : '{{ route('riwayat.store') }}'"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <template x-if="isEditing"><input type="hidden" name="_method" value="PUT"></template>
                        <input type="hidden" name="jenis" x-model="formData.jenis">

                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center"
                            :class="formData.jenis === 'pengeluaran' ? 'bg-rose-50 dark:bg-rose-900/20' :
                                'bg-emerald-50 dark:bg-emerald-900/20'">
                            <h3 class="text-lg font-bold"
                                :class="formData.jenis === 'pengeluaran' ? 'text-rose-700 dark:text-rose-400' :
                                    'text-emerald-700 dark:text-emerald-400'"
                                x-text="isEditing ? 'Edit Data' : 'Tambah ' + (formData.jenis === 'pengeluaran' ? 'Pengeluaran' : 'Pendapatan')">
                            </h3>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Barang
                                    / Transaksi</label>
                                <input type="text" name="nama_barang" x-model="formData.nama_barang" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div x-show="formData.jenis === 'pengeluaran'">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                                <input type="text" name="kategori" x-model="formData.kategori" list="categoryList"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                                <datalist id="categoryList"><template x-for="cat in categories" :key="cat">
                                        <option :value="cat"></option>
                                    </template>
                                    <option value="Bahan Baku"></option>
                                    <option value="Operasional"></option>
                                    <option value="Gaji"></option>
                                </datalist>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label><input
                                        type="date" name="tanggal_pembelian" x-model="formData.tanggal_pembelian"
                                        required
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nominal
                                        (Rp)</label><input type="hidden" name="total_harga"
                                        x-model="formData.total_harga"><input type="text"
                                        @input="updateTotalHarga($event)" :value="displayTotalHarga" required
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 font-mono">
                                </div>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bukti
                                    (Opsional)</label><input type="file" name="bukti_pembayaran" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                            <div><label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                                <textarea name="keterangan" x-model="formData.keterangan" rows="2"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                            </div>
                        </div>

                        <div
                            class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 flex justify-end gap-3">
                            <button type="button" @click="showModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-bold text-white rounded-lg hover:shadow-lg transition-all"
                                :class="formData.jenis === 'pengeluaran' ? 'bg-rose-600 hover:bg-rose-700' :
                                    'bg-emerald-600 hover:bg-emerald-700'">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('financeApp', ({
                riwayats,
                scanResult,
                categories
            }) => ({
                riwayats: riwayats,
                scanResult: scanResult,
                categories: categories,
                showModal: false,
                isEditing: false,
                editId: null,
                filterType: 'all',
                currentPage: 1,
                itemsPerPage: 5,
                formData: {
                    nama_barang: '',
                    kategori: '',
                    tanggal_pembelian: '',
                    total_harga: '',
                    keterangan: '',
                    bukti_pembayaran: '',
                    jenis: 'pengeluaran'
                },

                get totalPengeluaran() {
                    return this.riwayats.filter(r => r.jenis === 'pengeluaran').reduce((acc,
                        curr) => acc + parseFloat(curr.total_harga), 0);
                },
                get totalPendapatan() {
                    return this.riwayats.filter(r => r.jenis === 'pendapatan').reduce((acc, curr) =>
                        acc + parseFloat(curr.total_harga), 0);
                },

                get filteredRiwayats() {
                    if (this.filterType === 'all') return this.riwayats;
                    return this.riwayats.filter(r => r.jenis === this.filterType);
                },

                get totalPages() {
                    return Math.ceil(this.filteredRiwayats.length / this.itemsPerPage);
                },
                get paginatedRiwayats() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredRiwayats.slice(start, end);
                },
                changePage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },

                confirmDelete(id) {
                    Swal.fire({
                        title: 'Hapus data ini?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: document.documentElement.classList.contains('dark') ?
                            '#1f2937' : '#fff',
                        color: document.documentElement.classList.contains('dark') ? '#f3f4f6' :
                            '#1f2937'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + id).submit();
                        }
                    })
                },

                init() {
                    if (this.scanResult) this.handleScanResult(this.scanResult);
                },

                handleScanResult(scanData) {
                    this.formData = {
                        nama_barang: (scanData.items && scanData.items.length > 0) ? scanData.items
                            .join(', ') : (scanData.merchant_name || 'Belanja'),
                        tanggal_pembelian: scanData.transaction_date || new Date().toISOString()
                            .split('T')[0],
                        total_harga: scanData.total_amount || 0,
                        keterangan: 'Scan Struk: ' + (scanData.merchant_name || ''),
                        jenis: 'pengeluaran'
                    };
                    this.showModal = true;
                },

                openAddModal(jenis) {
                    this.formData = {
                        nama_barang: '',
                        kategori: '',
                        tanggal_pembelian: new Date().toISOString().split('T')[0],
                        total_harga: '',
                        keterangan: '',
                        bukti_pembayaran: '',
                        jenis: jenis
                    };
                    this.isEditing = false;
                    this.showModal = true;
                },

                openEditModal(riwayat) {
                    this.formData = {
                        ...riwayat
                    };
                    this.isEditing = true;
                    this.editId = riwayat.id;
                    this.showModal = true;
                },

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID').format(angka);
                },
                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short'
                    });
                },

                get displayTotalHarga() {
                    return this.formData.total_harga ? 'Rp ' + this.formatRupiah(this.formData
                        .total_harga) : '';
                },
                updateTotalHarga(event) {
                    const rawValue = event.target.value.replace(/[^0-9]/g, '');
                    this.formData.total_harga = rawValue;
                    event.target.value = 'Rp ' + this.formatRupiah(rawValue);
                },
                triggerScan() {
                    document.getElementById('scanInput').click();
                },
                submitScan() {
                    document.getElementById('scanForm').submit();
                }
            }));
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            function isDarkMode() {
                return document.documentElement.classList.contains('dark');
            }

            function getThemeColors() {
                return isDarkMode() ? {
                    background: '#1f2937',
                    color: '#f3f4f6'
                } : {
                    background: '#ffffff',
                    color: '#1f2937'
                };
            }

            @if (session('success'))
                const colors = getThemeColors();
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}",
                    background: colors.background,
                    color: colors.color
                });
            @endif

            @if (session('error'))
                const colors = getThemeColors();
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}",
                    background: colors.background,
                    color: colors.color
                });
            @endif
        });
    </script>
@endsection
