@extends('layouts.app')

@section('header', 'Riwayat Transksi')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8" x-data="{
        activeTab: 'pengeluaran',
        showModal: false,
        isEditing: false,
        editId: null,
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
        calculateTotal() {
            const jumlah = parseFloat(this.formData.jumlah) || 0;
            const hargaSatuan = parseFloat(this.formData.harga_satuan) || 0;
            this.formData.total_harga = (jumlah * hargaSatuan).toString();
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
            this.formData = {
                nama_barang: riwayat.nama_barang,
                jumlah: riwayat.jumlah,
                harga_satuan: riwayat.harga_satuan,
                total_harga: riwayat.total_harga,
                inventori: riwayat.inventori,
                jenis: riwayat.jenis,
                metode_pembayaran: riwayat.metode_pembayaran,
                keterangan: riwayat.keterangan
            };
            this.isEditing = true;
            this.editId = riwayat.id;
            this.showModal = true;
        },
        get filteredRiwayats() {
            return {{ Js::from($riwayats) }}.filter(r => r.jenis === this.activeTab);
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Riwayat Transaksi</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Kelola riwayat pengeluaran dan pendapatan bisnis Anda.</p>
                </div>
                <!-- Add Button -->
                <button @click="openAddModal(activeTab)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span x-text="'Tambah ' + (activeTab === 'pengeluaran' ? 'Pengeluaran' : 'Pendapatan')"></span>
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
                    class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg flex items-center gap-3 shadow-sm"
                    role="alert">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif
            
            <!-- Tabs Navigation -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px">
                        <button @click="activeTab = 'pengeluaran'"
                            :class="activeTab === 'pengeluaran' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                                <span>Pengeluaran</span>
                            </div>
                        </button>
                        <button @click="activeTab = 'pendapatan'"
                            :class="activeTab === 'pendapatan' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                                <span>Pendapatan</span>
                            </div>
                        </button>
                    </nav>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga Satuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Inventori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metode Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="riwayat in filteredRiwayats" :key="riwayat.id">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white" x-text="riwayat.nama_barang"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="riwayat.jumlah"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="'Rp ' + riwayat.harga_satuan"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold" 
                                        :class="activeTab === 'pengeluaran' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'"
                                        x-text="'Rp ' + riwayat.total_harga"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="riwayat.inventori"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="riwayat.metode_pembayaran"></td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate" x-text="riwayat.keterangan || '-'"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="openEditModal(riwayat)"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <form :action="`{{ route('riwayat.index') }}/${riwayat.id}`" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="filteredRiwayats.length === 0">
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="mt-4 text-sm" x-text="'Belum ada data ' + (activeTab === 'pengeluaran' ? 'pengeluaran' : 'pendapatan')"></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="showModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto" 
            style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75" 
                    @click="showModal = false"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <form :action="isEditing ? `{{ route('riwayat.index') }}/${editId}` : '{{ route('riwayat.store') }}'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="jenis" x-model="formData.jenis">

                        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" 
                                x-text="isEditing ? 'Edit Data' : 'Tambah ' + (formData.jenis === 'pengeluaran' ? 'Pengeluaran' : 'Pendapatan')"></h3>
                        </div>

                        <div class="px-6 py-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Barang</label>
                                    <input type="text" name="nama_barang" x-model="formData.nama_barang" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Inventori</label>
                                    <input type="text" name="inventori" x-model="formData.inventori" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah</label>
                                    <input type="text" name="jumlah" x-model="formData.jumlah" @input="calculateTotal()" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Satuan</label>
                                    <input type="text" name="harga_satuan" x-model="formData.harga_satuan" @input="calculateTotal()" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Harga</label>
                                    <input type="text" name="total_harga" x-model="formData.total_harga" readonly
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-600 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode Pembayaran</label>
                                    <input type="text" name="metode_pembayaran" x-model="formData.metode_pembayaran" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                                <textarea name="keterangan" x-model="formData.keterangan" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"></textarea>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" @click="showModal = false"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                                <span x-text="isEditing ? 'Simpan Perubahan' : 'Tambah Data'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
