@extends('layouts.app')

@section('header', 'Catat Transaksi Kas')

@section('content')
    <div class="w-full py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">

            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Catat Transaksi Kas</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 ml-8">Tambahkan pengeluaran atau pemasukan bisnis Anda</p>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">Terjadi kesalahan:</h3>
                            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <form action="{{ route('expenses.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    {{-- Tanggal Transaksi --}}
                    <div>
                        <label for="transaction_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Transaksi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="transaction_date" name="transaction_date"
                            value="{{ old('transaction_date', now()->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-shadow">
                    </div>

                    {{-- Kategori (COA) --}}
                    <div>
                        <label for="coa_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kategori Transaksi <span class="text-red-500">*</span>
                        </label>
                        <select id="coa_id" name="coa_id" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-shadow">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($coaOptions as $type => $coas)
                                <optgroup label="{{ $type === 'INFLOW' ? '💰 PEMASUKAN' : '💸 PENGELUARAN' }}">
                                    @foreach ($coas as $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ old('coa_id') == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Pilih jenis pemasukan atau pengeluaran
                        </p>
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jumlah (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-3.5 text-gray-500 dark:text-gray-400 text-sm font-medium">Rp</span>
                            <input type="number" id="amount" name="amount" value="{{ old('amount') }}" required
                                min="1" step="1" placeholder="0"
                                class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-shadow">
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_method" name="payment_method" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-shadow">
                            <option value="">-- Pilih Metode --</option>
                            <option value="Kas" {{ old('payment_method') == 'Kas' ? 'selected' : '' }}>💵 Kas/Tunai
                            </option>
                            <option value="Transfer Bank" {{ old('payment_method') == 'Transfer Bank' ? 'selected' : '' }}>
                                🏦 Transfer Bank</option>
                            <option value="E-Wallet" {{ old('payment_method') == 'E-Wallet' ? 'selected' : '' }}>📱
                                E-Wallet (GoPay, OVO, dll)</option>
                            <option value="Kartu Debit" {{ old('payment_method') == 'Kartu Debit' ? 'selected' : '' }}>💳
                                Kartu Debit</option>
                            <option value="Kartu Kredit" {{ old('payment_method') == 'Kartu Kredit' ? 'selected' : '' }}>
                                💳 Kartu Kredit</option>
                        </select>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Keterangan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="3" required placeholder="Contoh: Bayar listrik bulan Desember"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-shadow resize-none">{{ old('description') }}</textarea>
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Jelaskan detail transaksi ini</p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-3 rounded-lg shadow-sm hover:shadow transition-all active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Simpan Transaksi
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium px-6 py-3 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            {{-- Info Card --}}
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-1">💡 Tips</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-400">
                            Catat semua transaksi kas secara rutin untuk laporan keuangan yang akurat. Transaksi ini akan
                            otomatis muncul di Dashboard Anda.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
