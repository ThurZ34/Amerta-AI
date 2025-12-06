@extends('layouts.app')

@section('header', 'Profil Saya')

@section('content')
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-2xl overflow-hidden">
            {{-- Header / Cover --}}
            <div class="relative h-32 bg-linear-to-r from-indigo-500 to-purple-600">
                <div class="absolute -bottom-12 left-8">
                    <div
                        class="relative h-24 w-24 rounded-full border-4 border-white dark:border-gray-800 bg-white shadow-md overflow-hidden">
                        @if ($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo"
                                class="h-full w-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                alt="Default Avatar" class="h-full w-full object-cover">
                        @endif
                    </div>
                </div>
            </div>

            <div class="pt-16 pb-8 px-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $user->email }}</p>

                @if (session('success'))
                    <div
                        class="mt-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="mt-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                required
                                class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email (Read Only) --}}
                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-400">Email tidak dapat diubah.</p>
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <label for="number_phone"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor
                                Telepon</label>
                            <input type="text" name="number_phone" id="number_phone"
                                value="{{ old('number_phone', $user->number_phone) }}" placeholder="Contoh: 08123456789"
                                class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            @error('number_phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label for="birthday"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Lahir</label>
                            <input type="date" name="birthday" id="birthday"
                                value="{{ old('birthday', $user->birthday ? $user->birthday->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            @error('birthday')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label for="gender"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis
                                Kelamin</label>
                            <select name="gender" id="gender"
                                class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Foto Profil Input --}}
                        <div>
                            <label for="photo"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto Profil
                                Baru</label>
                            <input type="file" name="photo" id="photo" accept="image/*"
                                class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300">
                            <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG. Maksimal 2MB.</p>
                            @error('photo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat
                            Lengkap</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-indigo-500/30 transition-all hover:scale-[1.02] focus:ring-4 focus:ring-indigo-500/50">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
