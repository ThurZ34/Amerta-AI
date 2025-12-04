@extends('layouts.app')

@section('header', 'Riwayat Transaksi')

@section('content')
    <div class="min-h-screen bg-gray-50/50 dark:bg-gray-950 py-8 px-4" x-data="{
        activeTab: 'pengeluaran',
        showModal: false,
        isEditing: false,
        editId: null,
        // Data Dummy untuk kalkulasi total (Nanti bisa diganti backend logic)
        riwayats: {{ Js::from($riwayats) }},

        formData: {
            nama_barang: '',
            jumlah: '',
            harga_satuan: '',
            total_harga: '',
            inventori: '',
            jenis: 'pengeluaran',
            metode_pembayaran: '',
            keterangan: ''
        },

        init() {
        },

        // Hitung Total Otomatis saat ngetik
        calculateTotal() {
            const jumlah = parseFloat(this.formData.jumlah) || 0;
            const hargaSatuan = parseFloat(this.formData.harga_satuan) || 0;
            this.formData.total_harga = (jumlah * hargaSatuan).toString();
        },

        // Filter Data di Frontend (Bisa juga dari backend)
        get filteredRiwayats() {
            return this.riwayats.filter(r => r.jenis === this.activeTab);
        },

        // Hitung Total Uang di Tab Aktif
        get currentTotal() {
            return this.filteredRiwayats.reduce((acc, curr) => acc + parseFloat(curr.total_harga), 0);
        },

        openAddModal(jenis) {
            this.formData = {
                nama_barang: '',
                jumlah: '',
                harga_satuan: '',
                total_harga: '',
                inventori: '',
                jenis: jenis,
                metode_pembayaran: '',
                keterangan: ''
            };
            this.isEditing = false;
            this.showModal = true;
        },

        openEditModal(riwayat) {
            this.formData = { ...riwayat }; // Spread operator untuk copy object
            this.isEditing = true;
            this.editId = riwayat.id;
            this.showModal = true;
        },

        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
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

        // Handle harga satuan input
        updateHargaSatuan(event) {
            const rawValue = this.parseCurrency(event.target.value);
            this.formData.harga_satuan = rawValue;
            event.target.value = this.formatCurrency(rawValue);
            this.calculateTotal();
        },

        // Get formatted display value for harga satuan
        get displayHargaSatuan() {
            return this.formatCurrency(this.formData.harga_satuan);
        },

        // Get formatted display value for total harga
        get displayTotalHarga() {
            return this.formatCurrency(this.formData.total_harga);
        },
    }">
        <div class="max-w-2xl mx-auto space-y-6">

            <div class="relative overflow-hidden rounded-3xl p-6 shadow-xl transition-all duration-500"
                :class="activeTab === 'pengeluaran' ? 'bg-gradient-to-br from-rose-500 to-orange-600' :
                    'bg-gradient-to-br from-emerald-500 to-teal-600'">

                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-black/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 text-gray-900 dark:text-white text-center">
                    <p class="text-sm font-medium opacity-90 uppercase tracking-wider"
                        x-text="activeTab === 'pengeluaran' ? 'Total Pengeluaran' : 'Total Pendapatan'"></p>
                    <h2 class="text-4xl font-black mt-2 tracking-tight">
                        Rp <span x-text="formatRupiah(currentTotal)"></span>
                    </h2>

                    <div class="mt-6 flex justify-center gap-3">
                        <button @click="openAddModal(activeTab)"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-sm font-bold rounded-xl shadow-lg hover:scale-105 active:scale-95 transition-transform"
                            :class="activeTab === 'pengeluaran' ? 'text-rose-600' : 'text-emerald-600'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Tambah Manual</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-1 bg-gray-200 dark:bg-gray-800 rounded-xl flex relative">
                <button @click="activeTab = 'pengeluaran'"
                    class="flex-1 py-2.5 text-sm font-bold rounded-lg transition-all duration-300 flex items-center justify-center gap-2"
                    :class="activeTab === 'pengeluaran' ? 'bg-white dark:bg-gray-700 text-rose-600 shadow-sm' :
                        'text-gray-500 hover:text-gray-700 dark:text-gray-400'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    Pengeluaran
                </button>
                <button @click="activeTab = 'pendapatan'"
                    class="flex-1 py-2.5 text-sm font-bold rounded-lg transition-all duration-300 flex items-center justify-center gap-2"
                    :class="activeTab === 'pendapatan' ? 'bg-white dark:bg-gray-700 text-emerald-600 shadow-sm' :
                        'text-gray-500 hover:text-gray-700 dark:text-gray-400'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    Pendapatan
                </button>
            </div>

            <div class="space-y-3 pb-20">
                <template x-for="riwayat in filteredRiwayats" :key="riwayat.id">
                    <div
                        class="group bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all duration-200 flex items-center justify-between">

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 transition-colors"
                                :class="activeTab === 'pengeluaran' ? 'bg-rose-50 text-rose-500 dark:bg-rose-900/20' :
                                    'bg-emerald-50 text-emerald-500 dark:bg-emerald-900/20'">
                                <svg x-show="activeTab === 'pengeluaran'" class="w-6 h-6" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg> <svg x-show="activeTab === 'pendapatan'" class="w-6 h-6" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <h3 class="font-bold text-gray-900 dark:text-white truncate" x-text="riwayat.nama_barang">
                                </h3>
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    <span class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700 font-medium"
                                        x-text="riwayat.metode_pembayaran"></span>
                                    <span>•</span>
                                    <span x-text="riwayat.jumlah + ' unit'"></span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="font-black text-base"
                                :class="activeTab === 'pengeluaran' ? 'text-rose-600 dark:text-rose-400' :
                                    'text-emerald-600 dark:text-emerald-400'"
                                x-text="'Rp ' + formatRupiah(riwayat.total_harga)">
                            </p>

                            <div
                                class="flex items-center justify-end gap-3 mt-1 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openEditModal(riwayat)"
                                    class="text-xs font-medium hover:text-indigo-600 hover:underline"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg></button>
                                <form :action="`{{ route('riwayat.index') }}/${riwayat.id}`" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-xs font-medium hover:text-red-500 transition-colors"
                                        onclick="return confirm('Hapus data ini?')"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg></button>
                                </form>
                            </div>
                        </div>

                    </div>
                </template>

                <div x-show="filteredRiwayats.length === 0" class="text-center py-12" x-cloak>
                    <div
                        class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada transaksi.</p>
                </div>
            </div>
        </div>

        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
                <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="showModal = false">
                </div>

                <div class="relative bg-white dark:bg-gray-900 rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden transform transition-all"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95">

                    <form :action="isEditing ? `{{ route('riwayat.index') }}/${editId}` : '{{ route('riwayat.store') }}'"
                        method="POST">
                        @csrf
                        <template x-if="isEditing"><input type="hidden" name="_method" value="PUT"></template>
                        <input type="hidden" name="jenis" x-model="formData.jenis">

                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center"
                            :class="formData.jenis === 'pengeluaran' ? 'bg-rose-50 dark:bg-rose-900/20' :
                                'bg-emerald-50 dark:bg-emerald-900/20'">
                            <h3 class="text-lg font-bold"
                                :class="formData.jenis === 'pengeluaran' ? 'text-rose-700 dark:text-rose-400' :
                                    'text-emerald-700 dark:text-emerald-400'"
                                x-text="isEditing ? 'Edit Data' : 'Tambah ' + (formData.jenis === 'pengeluaran' ? 'Pengeluaran' : 'Pendapatan')">
                            </h3>
                            <button type="button" @click="showModal = false"
                                class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg></button>
                        </div>

                        <div class="p-6 space-y-5 max-h-[65vh] overflow-y-auto custom-scrollbar">

                            <div class="text-center">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total
                                    Harga</label>
                                <input type="hidden" name="total_harga" x-model="formData.total_harga">
                                <div class="w-full text-center text-3xl font-black bg-transparent p-0 text-gray-900 dark:text-white"
                                    x-text="displayTotalHarga || 'Rp. 0'"></div>
                                <p class="text-xs text-gray-400 italic mt-1">*Otomatis dihitung</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Jumlah</label>
                                    <input type="number" name="jumlah" x-model="formData.jumlah"
                                        @input="calculateTotal()" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-shadow">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Harga Satuan</label>
                                    <input type="hidden" name="harga_satuan" x-model="formData.harga_satuan">
                                    <input type="text"
                                        @input="updateHargaSatuan($event)"
                                        :value="displayHargaSatuan"
                                        required
                                        placeholder="Rp. 0"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-shadow">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Nama Barang</label>
                                <input type="text" name="nama_barang" x-model="formData.nama_barang" required
                                    placeholder="Cth: Stok Gula"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-shadow">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Inventori</label>
                                    <input type="text" name="inventori" x-model="formData.inventori" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Pembayaran</label>
                                    <select name="metode_pembayaran" x-model="formData.metode_pembayaran" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Pilih...</option>
                                        <option value="Tunai">Tunai</option>
                                        <option value="Transfer">Transfer</option>
                                        <option value="E-Wallet">E-Wallet</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">Keterangan
                                    (Opsional)</label>
                                <textarea name="keterangan" x-model="formData.keterangan" rows="2"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                            <button type="submit"
                                class="w-full py-3 rounded-xl font-bold text-white shadow-lg shadow-indigo-500/30 transition-all hover:scale-[1.02] active:scale-95"
                                :class="formData.jenis === 'pengeluaran' ? 'bg-rose-600 hover:bg-rose-700' :
                                    'bg-emerald-600 hover:bg-emerald-700'">
                                <span x-text="isEditing ? 'Simpan Perubahan' : 'Simpan Transaksi'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
