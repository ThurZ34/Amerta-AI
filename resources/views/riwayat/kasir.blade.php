@extends('layouts.app')

@section('header', 'Kasir')

@section('content')
    <div class="h-[calc(100vh-64px)] bg-gray-50 dark:bg-gray-900 font-sans overflow-hidden flex flex-col md:flex-row"
        x-data="cashier()">

        {{-- LEFT COLUMN: PRODUCTS --}}
        <div class="flex-1 flex flex-col h-full relative z-0">

            {{-- Search & Filter Bar --}}
            <div class="px-6 pt-6 pb-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-2 flex gap-3">
                    <div class="flex-1 relative">
                        <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" x-model="search" placeholder="Cari nama produk..."
                            class="w-full pl-10 pr-4 py-2.5 bg-transparent border-none focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm">
                    </div>
                    {{-- Optional: Category Filter Dropdown could go here --}}
                </div>
            </div>

            {{-- Scrollable Product Grid --}}
            <div class="flex-1 overflow-y-auto px-6 py-4 scrollbar-hide">
                <div class="max-w-4xl mx-auto">
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 pb-24 md:pb-6">
                        @foreach ($products as $product)
                            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden flex flex-col h-full"
                                x-show="!search || '{{ strtolower($product->nama_produk) }}'.includes(search.toLowerCase())"
                                @click="addToCart({{ Js::from([
                                    'id' => $product->id,
                                    'nama_produk' => $product->nama_produk,
                                    'harga_jual' => $product->harga_jual,
                                    'harga_coret' => $product->harga_coret ?? 0,
                                    'modal' => $product->modal,
                                    'gambar_url' => $product->gambar ? Storage::url($product->gambar) : null,
                                ]) }})">

                                <div class="aspect-4/3 w-full bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                                    @if ($product->gambar)
                                        <img src="{{ Storage::url($product->gambar) }}" alt="{{ $product->nama_produk }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif

                                    {{-- Promo Badge --}}
                                    @if ($product->harga_coret > 0 || ($product->is_promo ?? false))
                                        <span
                                            class="absolute top-2 right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-sm">PROMO</span>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="p-4 flex-1 flex flex-col">
                                    <h3
                                        class="text-sm font-semibold text-gray-800 dark:text-gray-100 line-clamp-2 mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors h-[2.5em]">
                                        {{ $product->nama_produk }}
                                    </h3>
                                    <div class="mt-auto space-y-3">
                                        <div class="flex flex-col">
                                            @if ($product->harga_coret > 0)
                                                <span class="text-xs text-gray-400 line-through">Rp
                                                    {{ number_format($product->harga_coret, 0, ',', '.') }}</span>
                                            @endif
                                            <p class="text-indigo-600 dark:text-indigo-400 font-bold text-base">
                                                Rp <span x-text="formatNumber({{ $product->harga_jual }})"></span>
                                            </p>
                                        </div>

                                        {{-- Add Button (Always Visible) --}}
                                        <button
                                            class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium text-xs shadow-sm hover:shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Empty Search State --}}
                        <div x-show="productsFilteredCount === 0" class="col-span-full py-12 text-center text-gray-400"
                            x-cloak>
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p>Produk tidak ditemukan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: CART --}}
        {{-- Mobile Float Button --}}
        <div class="fixed bottom-6 right-6 md:hidden z-50">
            <button @click="showCart = !showCart"
                class="relative bg-indigo-600 text-white p-4 rounded-full shadow-2xl hover:bg-indigo-700 transition-transform active:scale-95 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <div x-show="cartItemCount > 0" x-transition.scale
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full border-2 border-white dark:border-gray-900 min-w-[20px] text-center"
                    x-text="cartItemCount"></div>
            </button>
        </div>

        {{-- Cart Sidebar --}}
        <div class="fixed inset-y-0 right-0 w-full md:w-96 bg-white dark:bg-gray-900 shadow-2xl transform transition-transform duration-300 z-40 md:relative md:transform-none md:shadow-none border-l border-gray-200 dark:border-gray-800 flex flex-col"
            :class="{ 'translate-x-0': showCart, 'translate-x-full md:translate-x-0': !showCart }" x-cloak>

            {{-- Mobile Close --}}
            <div class="md:hidden flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Pesanan Saat Ini</h2>
                <button @click="showCart = false"
                    class="p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            {{-- Desktop Header --}}
            <div
                class="hidden md:flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-800 bg-white/50 dark:bg-gray-900/50 backdrop-blur">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Keranjang</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" x-text="cartItemCount + ' Item di keranjang'">
                    </p>
                </div>
                <button @click="cart = []" x-show="cart.length > 0"
                    class="text-xs text-red-500 hover:text-red-700 font-medium">Reset</button>
            </div>

            {{-- Cart Items --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50 dark:bg-gray-900/50">
                <template x-if="cart.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-center p-8 opacity-40">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png"
                            class="w-48 mb-4 grayscale" alt="Empty Cart">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Keranjang Kosong</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pilih produk di sebelah kiri</p>
                    </div>
                </template>

                <template x-for="(item, index) in cart" :key="item.id">
                    <div
                        class="group bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-500/30 dark:hover:border-indigo-500/30 shadow-sm transition-all">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2"
                                    x-text="item.nama_produk"></h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">Rp <span
                                            x-text="formatNumber(item.harga_jual * item.qty)"></span></span>
                                    <template x-if="item.harga_coret > 0">
                                        <span class="text-xs text-gray-400 line-through">Rp <span
                                                x-text="formatNumber(item.harga_coret * item.qty)"></span></span>
                                    </template>
                                </div>
                            </div>
                            <button @click="cart.splice(index, 1)"
                                class="text-gray-300 hover:text-red-500 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-3 flex items-center justify-between">
                            <div class="text-xs text-gray-400 dark:text-gray-500">@ <span
                                    x-text="formatNumber(item.harga_jual)"></span></div>
                            <div
                                class="flex items-center bg-gray-50 dark:bg-gray-900 rounded-lg p-0.5 border border-gray-200 dark:border-gray-700">
                                <button @click="updateQuantity(index, -1)"
                                    class="w-7 h-7 flex items-center justify-center rounded-md text-gray-500 hover:bg-white dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span class="w-8 text-center text-sm font-semibold text-gray-900 dark:text-white"
                                    x-text="item.qty"></span>
                                <button @click="updateQuantity(index, 1)"
                                    class="w-7 h-7 flex items-center justify-center rounded-md text-gray-500 hover:bg-white dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Footer (Subtotal) --}}
            <div class="p-6 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 shadow-[0_-5px_20px_rgba(0,0,0,0.05)] z-20"
                x-show="cart.length > 0" x-transition:enter="transition ease-out duration-300 transform translate-y-full"
                x-transition:enter-end="translate-y-0">

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span class="font-medium">Rp <span x-text="formatNumber(total)"></span></span>
                    </div>
                    {{-- Tax or Discount rows could go here --}}
                    <div class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-800">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                        <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">
                            Rp <span x-text="formatNumber(total)"></span>
                        </span>
                    </div>
                </div>

                {{-- Pay Button triggers Modal --}}
                <button @click="openPaymentModal()"
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all active:scale-95 flex justify-center items-center gap-2 text-lg">
                    <span>Bayar</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- PAYMENT MODAL --}}
        <div x-show="showPaymentModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-end md:items-center justify-center min-h-screen px-4 pb-4 md:py-8">
                <div x-show="showPaymentModal" x-transition.opacity @click="showPaymentModal = false"
                    class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

                <div x-show="showPaymentModal" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="translate-y-full md:translate-y-10 md:opacity-0"
                    x-transition:enter-end="translate-y-0 md:opacity-100"
                    class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-t-2xl md:rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                    {{-- Modal Header --}}
                    <div
                        class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pembayaran</h3>
                        <button @click="showPaymentModal = false" class="text-gray-400 hover:text-gray-600"><svg
                                class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 space-y-6 flex-1 overflow-y-auto">

                        {{-- Total Display --}}
                        <div class="text-center">
                            <span class="text-sm text-gray-500 uppercase tracking-wider">Total Tagihan</span>
                            <div class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Rp <span
                                    x-text="formatNumber(total)"></span></div>
                        </div>

                        {{-- Payment Method Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Metode
                                Pembayaran</label>
                            <div class="grid grid-cols-3 gap-3">
                                <template x-for="method in ['Tunai', 'QRIS', 'Transfer']">
                                    <button type="button" @click="paymentMethod = method; calculateChange()"
                                        :class="paymentMethod === method ?
                                            'bg-indigo-600 text-white border-indigo-600 ring-2 ring-indigo-300 dark:ring-indigo-900' :
                                            'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600'"
                                        class="py-3 px-2 rounded-xl border font-medium text-sm transition-all focus:outline-none"
                                        x-text="method">
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Cash Input Section (Only for 'Tunai') --}}
                        <div x-show="paymentMethod === 'Tunai'" x-collapse>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Uang
                                Diterima</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                                <input type="number" x-model.number="cashReceived" @input="calculateChange"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="0">
                            </div>

                            {{-- Quick Cash Buttons --}}
                            <div class="flex gap-2 mt-3 overflow-x-auto pb-1 scrollbar-hide">
                                <template x-for="amount in quickAmounts">
                                    <button @click="cashReceived = amount; calculateChange()"
                                        class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-600 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 hover:text-indigo-600 whitespace-nowrap transition-colors"
                                        x-text="formatNumber(amount)">
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Change Display --}}
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 flex justify-between items-center"
                            :class="{
                                'bg-red-50 dark:bg-red-900/20 text-red-600': change <
                                    0,
                                'bg-green-50 dark:bg-green-900/20 text-green-600': change >= 0
                            }">
                            <span class="font-medium text-sm" x-text="change < 0 ? 'Kurang Bayar' : 'Kembalian'"></span>
                            <span class="font-bold text-xl">Rp <span
                                    x-text="formatNumber(Math.abs(change))"></span></span>
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <form action="{{ route('operasional.riwayat-keuangan.store') }}" method="POST"
                            @submit.prevent="submitTransaction">
                            @csrf
                            <input type="hidden" name="jenis" value="pendapatan">
                            <input type="hidden" name="tanggal_pembelian" value="{{ date('Y-m-d') }}">
                            <input type="hidden" name="nama_barang" value="Penjualan Kasir">
                            <input type="hidden" name="total_harga" :value="total"> {{-- FIXED: Send Total Revenue, NOT Profit --}}
                            <input type="hidden" name="profit" :value="totalProfit"> {{-- You might need to add this column to DB or handled in Controller --}}
                            <input type="hidden" name="keterangan" :value="generateDescription()">
                            <input type="hidden" name="items" :value="JSON.stringify(cart)">
                            <input type="hidden" name="metode_pembayaran" :value="paymentMethod"> {{-- Optional: Add column to table --}}

                            <button type="submit" :disabled="paymentMethod === 'Tunai' && change < 0"
                                class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-lg transition-all flex justify-center items-center gap-2">
                                <span>Selesaikan Transaksi</span>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function cashier() {
            return {
                cart: [],
                showCart: false,
                search: '',
                showPaymentModal: false,
                paymentMethod: 'Tunai',
                cashReceived: 0,
                change: 0,

                get productsFilteredCount() {
                    const term = this.search.toLowerCase();
                    return document.querySelectorAll('.group[x-show]:not([style="display: none;"])').length;
                },

                get cartItemCount() {
                    return this.cart.reduce((acc, item) => acc + item.qty, 0);
                },

                get total() {
                    return this.cart.reduce((acc, item) => {
                        let price = item.harga_coret > 0 ? item.harga_coret : item.harga_jual;
                        return acc + (item.harga_jual * item.qty);
                    }, 0);
                },

                get totalProfit() {
                    return this.cart.reduce((acc, item) => {
                        const cost = item.modal || 0;
                        return acc + ((item.harga_jual - cost) * item.qty);
                    }, 0);
                },

                get quickAmounts() {
                    let amounts = [];
                    const t = this.total;
                    if (t > 0) {
                        amounts.push(t);
                        [5000, 10000, 20000, 50000, 100000].forEach(denom => {
                            if (t < denom) amounts.push(denom);
                            else {
                                const next = Math.ceil(t / denom) * denom;
                                if (!amounts.includes(next) && next > t) amounts.push(next);
                            }
                        });
                    }
                    return amounts.sort((a, b) => a - b).slice(0, 4);
                },

                addToCart(product) {
                    const existingItem = this.cart.find(item => item.id === product.id);
                    if (existingItem) {
                        existingItem.qty++;
                    } else {
                        this.cart.push({
                            ...product,
                            qty: 1
                        });
                    }

                    if (window.navigator && window.navigator.vibrate) {
                        window.navigator.vibrate(50);
                    }

                    if (window.innerWidth < 768) {
                    }
                },

                updateQuantity(index, change) {
                    const item = this.cart[index];
                    item.qty += change;

                    if (item.qty <= 0) {
                        this.cart.splice(index, 1);
                    }
                },

                openPaymentModal() {
                    if (this.cart.length === 0) return;
                    this.showPaymentModal = true;
                    this.cashReceived = 0;
                    this.calculateChange();
                },

                calculateChange() {
                    if (this.paymentMethod === 'Tunai') {
                        this.change = this.cashReceived - this.total;
                    } else {
                        this.change = 0;
                        this.cashReceived = this.total;
                    }
                },

                formatNumber(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                },

                generateDescription() {
                    const time = new Date().toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    const itemsDesc = this.cart.map(item => `${item.qty}x ${item.nama_produk}`).join(', ');
                    return `Kasir ${time} - ${itemsDesc}`;
                },

                submitTransaction(e) {
                    if (this.cart.length === 0) return;

                    e.target.submit();
                }
            }
        }
    </script>
@endsection
