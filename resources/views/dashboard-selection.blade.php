@extends('layouts.app')

@section('header', 'Pilih Jalur Anda')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center relative overflow-hidden px-4 sm:px-6 lg:px-8">
        {{-- Background Decor --}}
        <div class="absolute inset-0 w-full h-full">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
            <div class="absolute inset-0" style="background-image: radial-gradient(#6366f1 1px, transparent 1px); background-size: 32px 32px; opacity: 0.05;"></div>
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-blob"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-blob animation-delay-2000"></div>
        </div>

        <div class="w-full max-w-5xl relative z-10">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-4">
                    Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}!
                </h1>
                <p class="text-lg text-slate-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Langkah terakhir sebelum memulai. Tentukan peran Anda dalam ekosistem Amerta.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- CARD OWNER (Kiri) --}}
                <div class="group relative bg-white dark:bg-gray-800 rounded-[2rem] border border-gray-200 dark:border-gray-700 p-8 shadow-xl hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                     <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 dark:bg-indigo-900/20 rounded-bl-[4rem] transition-colors group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/40"></div>
                     <div class="relative z-10 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-bold uppercase tracking-wider border border-indigo-100 dark:border-indigo-800">Untuk Owner</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Rintis Bisnis Baru</h3>
                        <p class="text-slate-500 dark:text-gray-400 mb-8 leading-relaxed">
                            Saya ingin membuat ruang kerja baru, mengatur produk, dan memantau keuangan bisnis saya dari nol.
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('setup-bisnis') }}" class="flex items-center justify-center w-full py-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30 transition-all active:scale-95">
                                Mulai Setup Bisnis
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                            <p class="text-center text-xs text-slate-400 mt-4">Gratis uji coba fitur premium 14 hari</p>
                        </div>
                     </div>
                </div>

                {{-- CARD STAFF / GABUNG TIM (Kanan) --}}
                <div class="group relative bg-white dark:bg-gray-800 rounded-[2rem] border border-gray-200 dark:border-gray-700 p-8 shadow-xl hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 dark:bg-emerald-900/20 rounded-bl-[4rem] transition-colors group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/40"></div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider border border-emerald-100 dark:border-emerald-800">Untuk Staff</span>
                        </div>

                        {{-- LOGIC UI BERDASARKAN STATUS --}}

                        @if(Auth::user()->business_id)
                            {{-- KASUS 1: SUDAH DITERIMA (Punya Business ID) --}}
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Anda Sudah Diterima! 🎉</h3>
                            <p class="text-slate-500 dark:text-gray-400 mb-6">
                                Selamat! Permintaan bergabung Anda telah disetujui. Anda sekarang adalah bagian dari tim
                                <strong class="text-emerald-600 dark:text-emerald-400">{{ Auth::user()->business->nama_bisnis }}</strong>.
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('analisis.dashboard') }}" class="flex items-center justify-center w-full py-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30 transition-all active:scale-95">
                                    Masuk Main Menu
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                                </a>
                            </div>

                        @elseif(isset($pendingRequest) && $pendingRequest)
                            {{-- KASUS 2: MASIH MENUNGGU --}}
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Gabung Tim</h3>

                            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/50 rounded-xl p-4 mb-6 animate-pulse">
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div>
                                        <p class="text-sm font-bold text-amber-800 dark:text-amber-200">Menunggu Persetujuan</p>
                                        <p class="text-xs text-amber-600 dark:text-amber-400">Request dikirim ke: <strong>{{ $pendingRequest->business->nama_bisnis }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <form action="{{ route('dashboard-selection.cancel-request', $pendingRequest->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-3 rounded-xl border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 font-semibold transition-colors">
                                        Batalkan Permintaan
                                    </button>
                                </form>
                            </div>

                        @else
                            {{-- KASUS 3: DEFAULT (INPUT KODE) --}}
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Gabung Tim</h3>
                            <p class="text-slate-500 dark:text-gray-400 mb-6">Saya memiliki kode undangan dari pemilik bisnis dan ingin bergabung sebagai staff.</p>
                            <div class="mt-auto">
                                <form action="{{ route('dashboard-selection.join') }}" method="POST" class="relative">
                                    @csrf
                                    <div class="relative group/input">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within/input:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                        </div>
                                        <input type="text" name="invite_code" required class="block w-full pl-11 pr-24 py-4 rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-slate-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-mono tracking-wider uppercase" placeholder="KODE-123">
                                        <button type="submit" class="absolute right-2 top-2 bottom-2 bg-white dark:bg-gray-800 text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-gray-700 px-4 rounded-lg text-sm font-bold shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
                                            Gabung
                                        </button>
                                    </div>
                                    @error('invite_code')
                                        <p class="mt-2 text-sm text-rose-600 dark:text-rose-400 flex items-center animate-pulse">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </form>
                                <p class="text-center text-xs text-slate-400 mt-4">Tanya pemilik bisnis untuk kode akses</p>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT SWEETALERT (TETAP SAMA) --}}
    @if(session('success_request'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Permintaan Terkirim!',
                text: '{{ session("success_request") }}',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Oke, saya tunggu',
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
            });
        </script>
    @endif
@endsection
