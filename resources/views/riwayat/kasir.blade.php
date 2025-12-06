@extends('layouts.app')

@section('header', 'Kasir')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900" x-data="cashier()">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('riwayat.index') }}" class="mr-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Kasir</h1>
                </div>
                
                {{-- Cart Summary (Mobile) --}}
                <div class="flex items-center md:hidden">
                    <button @click="showCart = !showCart" class="relative p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span x-show="cart.length > 0" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full" x-text="cartItemCount"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row gap-6">
            
            {{-- Product Grid --}}
            <div class="w-full md:w-2/3">
                {{-- Search Bar --}}
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" x-model="search" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Products --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($products as $product)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-shadow cursor-pointer overflow-hidden border border-gray-200 dark:border-gray-700 flex flex-col h-full"
                             x-show="matchesSearch('{{ strtolower($product->nama_produk) }}')"
                             @click="addToCart({{ $product }})">
                            
                            <div class="aspect-w-1 aspect-h-1 w-full bg-gray-200 dark:bg-gray-700 relative">
                                @if($product->gambar)
                                    <img src="{{ Storage::url($product->gambar) }}" alt="{{ $product->nama_produk }}" class="w-full h-32 object-cover">
                                @else
                                    <div class="w-full h-32 flex items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                                    Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="p-4 flex-grow flex flex-col justify-between">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2 mb-1">{{ $product->nama_produk }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Stok: {{ $product->stok ?? '-' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Cart Sidebar --}}
            <div class="w-full md:w-1/3 fixed inset-0 md:relative z-40 md:z-auto" :class="{'hidden': !showCart && window.innerWidth < 768, 'block': showCart || window.innerWidth >= 768}">
                <div class="absolute inset-0 bg-gray-800 bg-opacity-75 md:hidden" @click="showCart = false"></div>
                
                <div class="relative h-full md:h-auto bg-white dark:bg-gray-800 md:rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 flex flex-col">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Keranjang</h2>
                        <button @click="showCart = false" class="md:hidden text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-4" style="max-height: calc(100vh - 250px);">
                        <template x-if="cart.length === 0">
                            <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p>Keranjang kosong</p>
                            </div>
                        </template>

                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="flex justify-between items-start pb-4 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                <div class="flex-1 pr-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white" x-text="item.nama_produk"></h4>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Rp <span x-text="formatNumber(item.harga_jual)"></span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button @click="updateQuantity(index, -1)" class="p-1 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <span class="text-sm font-medium w-6 text-center text-gray-900 dark:text-white" x-text="item.qty"></span>
                                    <button @click="updateQuantity(index, 1)" class="p-1 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 rounded-b-xl">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600 dark:text-gray-400">Total</span>
                            <span class="text-xl font-bold text-gray-900 dark:text-white">Rp <span x-text="formatNumber(total)"></span></span>
                        </div>
                        
                        <form action="{{ route('riwayat.store') }}" method="POST" @submit.prevent="submitTransaction">
                            @csrf
                            <input type="hidden" name="jenis" value="pendapatan">
                            <input type="hidden" name="tanggal_pembelian" value="{{ date('Y-m-d') }}">
                            <input type="hidden" name="nama_barang" value="Penjualan Kasir">
                            <input type="hidden" name="total_harga" :value="totalProfit">
                            <input type="hidden" name="keterangan" :value="generateDescription()">
                            
                            <button type="submit" 
                                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="cart.length === 0">
                                Bayar Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function cashier() {
        return {
            search: '',
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
            
            matchesSearch(productName) {
                return productName.includes(this.search.toLowerCase());
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
                
                // On mobile, show cart when adding first item
                if (window.innerWidth < 768 && this.cart.length === 1) {
                    this.showCart = true;
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
                
                // Submit the form
                e.target.submit();
            }
        }
    }
</script>
@endsection
