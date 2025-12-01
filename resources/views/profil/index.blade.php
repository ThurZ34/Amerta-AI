@extends('layouts.app')

@section('content')
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Profil Bisnis</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Kelola informasi bisnis Anda di sini.</p>
            </div>
        </div>

        <div class="mt-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('profil_bisnis.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Nama Bisnis -->
                            <div class="sm:col-span-4">
                                <label for="nama_bisnis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Bisnis</label>
                                <div class="mt-1">
                                    <input type="text" name="nama_bisnis" id="nama_bisnis" value="{{ old('nama_bisnis', $business->nama_bisnis ?? '') }}"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">
                                </div>
                                @error('nama_bisnis')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="sm:col-span-3">
                                <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                                <div class="mt-1">
                                    <input type="text" name="kategori" id="kategori" value="{{ old('kategori', $business->kategori ?? '') }}"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">
                                </div>
                                @error('kategori')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Target Pasar -->
                            <div class="sm:col-span-3">
                                <label for="target_pasar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Target Pasar</label>
                                <div class="mt-1">
                                    <input type="text" name="target_pasar" id="target_pasar" value="{{ old('target_pasar', $business->target_pasar ?? '') }}"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">
                                </div>
                                @error('target_pasar')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jumlah Tim -->
                            <div class="sm:col-span-2">
                                <label for="jumlah_tim" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Tim</label>
                                <div class="mt-1">
                                    <input type="number" name="jumlah_tim" id="jumlah_tim" value="{{ old('jumlah_tim', $business->jumlah_tim ?? '') }}"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">
                                </div>
                                @error('jumlah_tim')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Telepon -->
                            <div class="sm:col-span-3">
                                <label for="telepon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telepon</label>
                                <div class="mt-1">
                                    <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $business->telepon ?? '') }}"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">
                                </div>
                                @error('telepon')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="sm:col-span-6">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                                <div class="mt-1">
                                    <textarea id="alamat" name="alamat" rows="3"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">{{ old('alamat', $business->alamat ?? '') }}</textarea>
                                </div>
                                @error('alamat')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="sm:col-span-6">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                                <div class="mt-1">
                                    <textarea id="deskripsi" name="deskripsi" rows="4"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">{{ old('deskripsi', $business->deskripsi ?? '') }}</textarea>
                                </div>
                                @error('deskripsi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tujuan Utama -->
                            <div class="sm:col-span-6">
                                <label for="tujuan_utama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tujuan Utama</label>
                                <div class="mt-1">
                                    <textarea id="tujuan_utama" name="tujuan_utama" rows="3"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">{{ old('tujuan_utama', $business->tujuan_utama ?? '') }}</textarea>
                                </div>
                                @error('tujuan_utama')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-5">
                            <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
