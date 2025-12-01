<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup Bisnis - Amerta AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-2xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900">Setup Bisnis Anda</h2>
                <p class="mt-2 text-sm text-gray-600">Lengkapi profil bisnis Anda untuk melanjutkan.</p>
            </div>

            <form method="POST" action="{{ route('setup-bisnis.store') }}">
                @csrf

                <!-- Nama Bisnis -->
                <div class="mb-4">
                    <label for="nama_bisnis" class="block font-medium text-sm text-gray-700">Nama Bisnis</label>
                    <input id="nama_bisnis"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        type="text" name="nama_bisnis" value="{{ old('nama_bisnis') }}" required autofocus />
                    @error('nama_bisnis')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Bisnis -->
                <div class="mb-4">
                    <label for="status_bisnis" class="block font-medium text-sm text-gray-700">Status Bisnis</label>
                    <select id="status_bisnis" name="status_bisnis"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="">Pilih Status</option>
                        <option value="Baru Mulai" {{ old('status_bisnis') == 'Baru Mulai' ? 'selected' : '' }}>Baru
                            Mulai</option>
                        <option value="Sudah Berjalan" {{ old('status_bisnis') == 'Sudah Berjalan' ? 'selected' : '' }}>
                            Sudah Berjalan</option>
                    </select>
                    @error('status_bisnis')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori Bisnis -->
                <div class="mb-4">
                    <label for="kategori_bisnis" class="block font-medium text-sm text-gray-700">Kategori Bisnis
                        (Contoh: F&B, Fashion)</label>
                    <input id="kategori_bisnis"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        type="text" name="kategori_bisnis" value="{{ old('kategori_bisnis') }}" required />
                    @error('kategori_bisnis')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Channel Penjualan -->
                <div class="mb-4">
                    <label for="channel_penjualan" class="block font-medium text-sm text-gray-700">Channel
                        Penjualan</label>
                    <select id="channel_penjualan" name="channel_penjualan"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="">Pilih Channel</option>
                        <option value="Online" {{ old('channel_penjualan') == 'Online' ? 'selected' : '' }}>Online
                        </option>
                        <option value="Offline" {{ old('channel_penjualan') == 'Offline' ? 'selected' : '' }}>Offline
                        </option>
                        <option value="Hybrid" {{ old('channel_penjualan') == 'Hybrid' ? 'selected' : '' }}>Hybrid
                        </option>
                    </select>
                    @error('channel_penjualan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Range Omset -->
                <div class="mb-4">
                    <label for="range_omset" class="block font-medium text-sm text-gray-700">Range Omset Bulanan</label>
                    <input id="range_omset"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        type="text" name="range_omset" value="{{ old('range_omset') }}" required />
                    @error('range_omset')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Pasar -->
                <div class="mb-4">
                    <label for="target_pasar" class="block font-medium text-sm text-gray-700">Target Pasar</label>
                    <input id="target_pasar"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        type="text" name="target_pasar" value="{{ old('target_pasar') }}" required />
                    @error('target_pasar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jumlah Tim -->
                <div class="mb-4">
                    <label for="jumlah_tim" class="block font-medium text-sm text-gray-700">Jumlah Tim</label>
                    <input id="jumlah_tim"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        type="text" name="jumlah_tim" value="{{ old('jumlah_tim') }}" required />
                    @error('jumlah_tim')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tujuan Utama -->
                <div class="mb-4">
                    <label for="tujuan_utama" class="block font-medium text-sm text-gray-700">Tujuan Utama
                        Bisnis</label>
                    <input id="tujuan_utama"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        type="text" name="tujuan_utama" value="{{ old('tujuan_utama') }}" required />
                    @error('tujuan_utama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Masalah Utama -->
                <div class="mb-4">
                    <label for="masalah_utama" class="block font-medium text-sm text-gray-700">Masalah Utama
                        (Opsional)</label>
                    <textarea id="masalah_utama"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="masalah_utama" rows="3">{{ old('masalah_utama') }}</textarea>
                    @error('masalah_utama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit"
                        class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Simpan & Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
