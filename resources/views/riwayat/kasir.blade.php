@extends('layouts.app')

@section('header', 'Kasir')

@section('content')
    <div class="h-[calc(100vh-64px)] bg-gray-50 dark:bg-gray-900 font-sans overflow-hidden flex flex-col md:flex-row"
        x-data="cashier()">

        <div class="flex-1 flex flex-col h-full relative z-0">
            {{-- Scrollable Product Grid --}}
            <div class="flex-1 overflow-y-auto px-6 py-6 scrollbar-hide">
                <div class="max-w-4xl mx-auto">
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-6 pb-24 md:pb-6">
                        @foreach ($products as $product)
                            <div class="group relative bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden"
                                @click="addToCart({{ Js::from([
                                    'id' => $product->id,
                                    'nama_produk' => $product->nama_produk,
                                    'harga_jual' => $product->harga_jual,
                                    'modal' => $product->modal,
                                    'gambar_url' => $product->gambar ? Storage::url($product->gambar) : null,
                                ]) }})">

                                <div class="aspect-4/3 w-full bg-gray-100 dark:bg-gray-800 relative overflow-hidden">
                                    @if ($product->gambar)
                                        <img src="{{ Storage::url($product->gambar) }}" alt="{{ $product->nama_produk }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif

                                </div>

                                {{-- Content --}}
                                <div class="p-4">
                                    <h3
                                        class="text-sm font-semibold text-gray-800 dark:text-gray-100 line-clamp-1 mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $product->nama_produk }}</h3>
                                    <p class="text-indigo-600 dark:text-indigo-400 font-bold text-base">
                                        Rp <span x-text="formatNumber({{ $product->harga_jual }})"></span>
                                    </p>
                                </div>

                                {{-- Add Overlay --}}
                                <div
                                    class="absolute inset-x-0 bottom-0 top-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-4 bg-linear-to-t from-white/90 via-white/80 to-transparent dark:from-gray-900/90 dark:via-gray-900/80">
                                    <button
                                        class="w-full py-2 bg-indigo-600 text-white rounded-lg font-medium text-xs shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        @endforeach
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
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pesanan</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" x-text="cartItemCount + ' Item di keranjang'">
                    </p>
                </div>
                <button @click="cart = []" x-show="cart.length > 0"
                    class="text-xs text-red-500 hover:text-red-700 font-medium">Reset</button>
            </div>

            {{-- Cart Items --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <template x-if="cart.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-center p-8 opacity-40">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png"
                            class="w-48 mb-4 grayscale" alt="Empty Cart">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Keranjang Kosong</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada produk yang dipilih</p>
                    </div>
                </template>

                <template x-for="(item, index) in cart" :key="item.id">
                    <div
                        class="group bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-500/30 dark:hover:border-indigo-500/30 shadow-sm transition-all">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2"
                                    x-text="item.nama_produk"></h4>
                                <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400 mt-1">
                                    Rp <span x-text="formatNumber(item.harga_jual * item.qty)"></span>
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
                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                @ <span x-text="formatNumber(item.harga_jual)"></span>
                            </div>
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

            {{-- Footer --}}
            <div class="p-6 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800"
                x-show="cart.length > 0" x-transition:enter="transition ease-out duration-300 transform translate-y-full">
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span class="font-medium">Rp <span x-text="formatNumber(total)"></span></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                        <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">
                            Rp <span x-text="formatNumber(total)"></span>
                        </span>
                    </div>
                </div>

                <form action="{{ route('riwayat.store') }}" method="POST" @submit.prevent="submitTransaction">
                    @csrf
                    <input type="hidden" name="jenis" value="pendapatan">
                    <input type="hidden" name="tanggal_pembelian" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="nama_barang" value="Penjualan Kasir">
                    <input type="hidden" name="total_harga" :value="totalProfit">
                    <input type="hidden" name="keterangan" :value="generateDescription()">
                    <input type="hidden" name="items" :value="JSON.stringify(cart)">

                    <button type="submit"
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all active:scale-95 flex justify-center items-center gap-2">
                        <span>Proses Pembayaran</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function cashier() {
            return {
                cart: [],
                showCart: false,

                get cartItemCount() {
                    return this.cart.reduce((acc, item) => acc + item.qty, 0);
                },

                get total() {
                    return this.cart.reduce((acc, item) => acc + (item.harga_jual * item.qty), 0);
                },

                get totalProfit() {
                    return this.cart.reduce((acc, item) => {
                        const cost = item.modal || 0;
                        return acc + ((item.harga_jual - cost) * item.qty);
                    }, 0);
                },

                addToCart(product) {
                    const existingItem = this.cart.find(item => item.id === product.id);
                    if (existingItem) {
                        existingItem.qty++;
                    } else {
                        // Clone object agar reaktif
                        this.cart.push({
                            ...product,
                            qty: 1
                        });
                    }

                    // Haptic feedback if available (mobile)
                    if (window.navigator && window.navigator.vibrate) {
                        window.navigator.vibrate(50);
                    }
                },

                updateQuantity(index, change) {
                    const item = this.cart[index];
                    item.qty += change;

                    if (item.qty <= 0) {
                        this.cart.splice(index, 1);
                    }
                },

                formatNumber(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                },

                generateDescription() {
                    return this.cart.map(item => `${item.qty}x ${item.nama_produk}`).join(', ');
                },

                submitTransaction(e) {
                    if (this.cart.length === 0) return;
                    e.target.submit();
                }
            }
        }
    </script>
@endsection
