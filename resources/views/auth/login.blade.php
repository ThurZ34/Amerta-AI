@extends('auth.layout')

@section('title', 'Login')

@section('image_url', asset('images/banner_login.png'))
@section('content')

    <div class="text-left mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Login untuk melanjutkan</h1>
    </div>

    {{-- Google Login --}}
    <a href="{{ route('google.login') }}"
        class="w-full flex items-center justify-center gap-3 border border-gray-300 rounded-lg p-3 hover:bg-gray-50 transition">
        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="" class="w-5 h-5">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Masuk dengan Google</span>
    </a>

    {{-- Garis pemisah --}}
    <div class="relative flex py-3 items-center">
        <div class="grow border-t border-gray-200 dark:border-gray-700"></div>
        <span class="shrink-0 mx-4 text-gray-400 text-sm">atau</span>
        <div class="grow border-t border-gray-200 dark:border-gray-700"></div>
    </div>

    {{-- FORM LOGIN --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- EMAIL --}}
        <div>
            <div
                class="flex items-center gap-3 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-3 bg-white dark:bg-gray-800">
                <img src="{{ asset('images/email.png') }}" class="w-6 h-6 opacity-70">
                <input autocomplete="off" type="email" name="email" required autofocus
                    class="flex-1 outline-none bg-transparent dark:text-white placeholder-gray-400" placeholder="Email">
            </div>
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- PASSWORD + SHOW/HIDE --}}
        <div>
            <div
                class="relative flex items-center gap-3 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-3 bg-white dark:bg-gray-800">
                <img src="{{ asset('images/password.png') }}" class="w-6 h-6 opacity-70">

                <input id="loginPassword" type="password" name="password" required
                    class="flex-1 outline-none bg-transparent dark:text-white placeholder-gray-400 pr-10"
                    placeholder="Password">

                {{-- tombol show/hide password --}}
                <button type="button" onclick="toggleLoginPassword()"
                    class="absolute right-3 p-1 text-gray-400 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">

                    {{-- icon eye (show) --}}
                    <svg id="iconPasswordShow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        class="w-5 h-5">
                        <path
                            d="M2 12C3.8 7.8 7.5 5 12 5C16.5 5 20.2 7.8 22 12C20.2 16.2 16.5 19 12 19C7.5 19 3.8 16.2 2 12Z"
                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6" />
                    </svg>

                    {{-- icon eye-off (hide) --}}
                    <svg id="iconPasswordHide" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        class="w-5 h-5 hidden">
                        <path d="M3 3L21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                        <path d="M10.6 10.6C10.2 11 10 11.5 10 12C10 13.1 10.9 14 12 14C12.5 14 13 13.8 13.4 13.4"
                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7.4 7.5C5.5 8.4 3.9 10 3 12C4.8 16.2 8.5 19 13 19C14.3 19 15.6 18.7 16.7 18.2"
                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9.5 5.1C10.3 5 11.1 5 12 5C16.5 5 20.2 7.8 22 12C21.6 13 21.1 13.9 20.4 14.7"
                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex justify-between items-center mb-1">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300"></span>
            @if (Route::has('password.request'))
                <button type="button" onclick="openForgotModal()"
                    class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white">
                    Lupa Password ?
                </button>
            @endif
        </div>

        {{-- TOMBOL LOGIN BESAR --}}
        <button type="submit"
            class="w-full bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 text-lg rounded-xl transition shadow-md">
            LOGIN
        </button>

    </form>

    <div class="text-center text-sm text-gray-600 dark:text-gray-400 mt-2">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-purple-500 hover:underline">Daftar Sekarang</a>
    </div>
@endsection


{{-- ============================
     MODAL FORGOT PASSWORD (NEW UI)
============================= --}}
<div id="forgotModal"
    class="fixed inset-0 hidden z-50 items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 opacity-0">

    <div id="modalContent"
        class="relative w-full max-w-md mx-4 transition-all duration-300 transform scale-95 opacity-0">
        <div class="relative w-full max-w-md mx-4">
            {{-- Glow Background --}}
            <div class="pointer-events-none absolute inset-0 -z-10">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-purple-500/20 blur-3xl rounded-full"></div>
                <div class="absolute -bottom-8 -right-6 w-40 h-40 bg-indigo-500/20 blur-3xl rounded-full"></div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100/80 dark:border-slate-800/80 p-6 sm:p-7">

                {{-- HEADER + CLOSE --}}
                <div class="flex items-start justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-linear-to-tr from-purple-500 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-purple-500/30">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L3 7v10l9 5 9-5V7l-9-5Z" stroke="currentColor" stroke-width="1.6"
                                    stroke-linejoin="round" class="opacity-80" />
                                <path d="M8 11.5L11 14.5L16 9.5" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-white">Reset Password
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Ikuti 3 langkah sederhana.</p>
                        </div>
                    </div>

                    <button type="button" onclick="closeForgotModal()"
                        class="p-2 rounded-full text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>

                {{-- STEP INDICATOR (Dynamic) --}}
                <div class="flex items-center justify-between mb-6 text-xs font-medium relative">
                    {{-- Step 1 --}}
                    <div id="indicator1" class="flex items-center gap-2">
                        <span id="circle1"
                            class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-purple-500 text-white text-[10px]">1</span>
                        <span id="text1" class="text-slate-900 dark:text-white font-semibold">Kirim OTP</span>
                    </div>

                    <div class="h-px flex-1 mx-2 bg-slate-200 dark:bg-slate-700"></div>

                    {{-- Step 2 --}}
                    <div id="indicator2" class="flex items-center gap-2 opacity-50">
                        <span id="circle2"
                            class="inline-flex items-center justify-center w-5 h-5 rounded-full border border-slate-300 dark:border-slate-600 text-[10px] text-slate-500">2</span>
                        <span id="text2" class="text-slate-500 dark:text-slate-400">Verifikasi</span>
                    </div>

                    <div class="h-px flex-1 mx-2 bg-slate-200 dark:bg-slate-700"></div>

                    {{-- Step 3 --}}
                    <div id="indicator3" class="flex items-center gap-2 opacity-50">
                        <span id="circle3"
                            class="inline-flex items-center justify-center w-5 h-5 rounded-full border border-slate-300 dark:border-slate-600 text-[10px] text-slate-500">3</span>
                        <span id="text3" class="text-slate-500 dark:text-slate-400">Password Baru</span>
                    </div>
                </div>

                {{-- STEP 1: MASUKKAN EMAIL --}}
                <div id="stepEmail" class="space-y-4">
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-2">Masukkan email yang terdaftar untuk
                            menerima kode OTP.</p>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 6H20V18H4V6Z" stroke="currentColor" stroke-width="1.5"
                                        stroke-linejoin="round" />
                                    <path d="M4 7L12 12L20 7" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </span>
                            <input type="email" id="resetEmail"
                                class="w-full pl-10 pr-3 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                                      bg-slate-50 dark:bg-slate-900/60 text-sm text-slate-900 dark:text-white
                                      placeholder-slate-400 dark:placeholder-slate-500
                                      focus:outline-none focus:ring-2 focus:ring-purple-500/60 focus:border-purple-500 transition"
                                placeholder="contoh@mailkamu.com">
                        </div>
                    </div>
                    <button onclick="sendOTP()"
                        class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-500 to-indigo-500
                               hover:from-purple-600 hover:to-indigo-600 text-white text-sm font-semibold py-2.5
                               rounded-xl shadow-lg shadow-purple-500/25 transition-transform active:scale-95">
                        Kirim Kode OTP
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                {{-- STEP 2: MASUKKAN OTP (Improved UX) --}}
                <div id="stepOTP" class="hidden text-center space-y-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-1">Masukkan Kode OTP</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Cek kotak masuk atau spam email Anda.</p>
                    </div>

                    {{-- OTP Inputs Container --}}
                    <div class="flex justify-center gap-2 sm:gap-3" id="otp-container">
                        @for ($i = 1; $i <= 6; $i++)
                            <input type="text" maxlength="1" inputmode="numeric" id="otp{{ $i }}"
                                class="otp-input w-9 h-11 sm:w-10 sm:h-12 border border-slate-200 dark:border-slate-700 rounded-lg
                                      text-center text-lg font-semibold tracking-widest
                                      bg-slate-50 dark:bg-slate-900/60 text-slate-900 dark:text-white
                                      outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500 transition">
                        @endfor
                    </div>

                    <button onclick="verifyOTP()"
                        class="w-full inline-flex items-center justify-center gap-2 bg-purple-500 hover:bg-purple-600
                               text-white text-sm font-semibold py-2.5 rounded-xl shadow-md shadow-purple-500/25
                               transition-transform active:scale-95">
                        Verifikasi
                    </button>

                    <button onclick="backToEmail()"
                        class="w-full text-xs text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                        Kembali ubah email
                    </button>
                </div>

                {{-- STEP 3: RESET PASSWORD BARU --}}
                <div id="stepReset" class="hidden space-y-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-1">Buat Password Baru</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Minimal 8 karakter.</p>
                    </div>
                    <div class="space-y-3">
                        <input type="password" id="newPassword"
                            class="w-full px-3 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                                  bg-slate-50 dark:bg-slate-900/60 text-sm text-slate-900 dark:text-white
                                  placeholder-slate-400 dark:placeholder-slate-500
                                  focus:outline-none focus:ring-2 focus:ring-purple-500/60 focus:border-purple-500 transition"
                            placeholder="Password Baru">

                        <input type="password" id="confirmPassword"
                            class="w-full px-3 py-3 rounded-xl border border-slate-200 dark:border-slate-700
                                  bg-slate-50 dark:bg-slate-900/60 text-sm text-slate-900 dark:text-white
                                  placeholder-slate-400 dark:placeholder-slate-500
                                  focus:outline-none focus:ring-2 focus:ring-purple-500/60 focus:border-purple-500 transition"
                            placeholder="Konfirmasi Password">
                    </div>
                    <button onclick="saveNewPassword()"
                        class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-500 to-indigo-500
                               hover:from-purple-600 hover:to-indigo-600 text-white text-sm font-semibold py-2.5
                               rounded-xl shadow-lg shadow-purple-500/30 transition-transform active:scale-95 mt-1.5">
                        Simpan Password
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12.5L10 17.5L19 7.5" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // === 1. TOGGLE LOGIN PASSWORD SHOW/HIDE ===
    function toggleLoginPassword() {
        const input = document.getElementById('loginPassword');
        const iconShow = document.getElementById('iconPasswordShow');
        const iconHide = document.getElementById('iconPasswordHide');

        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            iconShow.classList.add('hidden');
            iconHide.classList.remove('hidden');
        } else {
            input.type = 'password';
            iconShow.classList.remove('hidden');
            iconHide.classList.add('hidden');
        }
    }

    // === 2. MODAL LOGIC ===
    function openForgotModal() {
        const modal = document.getElementById('forgotModal');
        const content = document.getElementById('modalContent');

        // 1. Buka dulu (hilangkan hidden)
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

        // Reset ke Step 1 setiap kali dibuka
        showStep(1);

        // 2. Beri jeda sangat sedikit agar transisi CSS jalan
        setTimeout(() => {
            // Fade In Background
            modal.classList.remove('opacity-0');

            // Scale Up & Fade In Content
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeForgotModal() {
        const modal = document.getElementById('forgotModal');
        const content = document.getElementById('modalContent');

        // 1. Mulai animasi keluar (Scale Down & Fade Out)
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        // 2. Tunggu animasi selesai (300ms sesuai duration-300 CSS), baru hide elemennya
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }

    // === 3. STEP INDICATOR LOGIC ===
    function showStep(stepNumber) {
        // Hide all steps
        document.getElementById('stepEmail').classList.add('hidden');
        document.getElementById('stepOTP').classList.add('hidden');
        document.getElementById('stepReset').classList.add('hidden');

        // Show requested step
        if (stepNumber === 1) document.getElementById('stepEmail').classList.remove('hidden');
        if (stepNumber === 2) document.getElementById('stepOTP').classList.remove('hidden');
        if (stepNumber === 3) document.getElementById('stepReset').classList.remove('hidden');

        // Update Indicator UI
        updateStepUI(stepNumber);
    }

    function updateStepUI(currentStep) {
        for (let i = 1; i <= 3; i++) {
            const indicator = document.getElementById('indicator' + i);
            const circle = document.getElementById('circle' + i);
            const text = document.getElementById('text' + i);

            // Jika step ini aktif atau sudah dilewati (opsional: aktif saja)
            if (i === currentStep) {
                // Style Active
                indicator.classList.remove('opacity-50');
                circle.className =
                    "inline-flex items-center justify-center w-5 h-5 rounded-full bg-purple-500 text-white text-[10px]";
                circle.innerText = i;
                text.className = "text-slate-900 dark:text-white font-semibold";
            } else if (i < currentStep) {
                // Style Completed (tetap ungu tapi mungkin icon check, disini kita samakan active dulu)
                indicator.classList.remove('opacity-50');
                circle.className =
                    "inline-flex items-center justify-center w-5 h-5 rounded-full bg-purple-500 text-white text-[10px]";
                text.className = "text-slate-900 dark:text-white font-medium";
            } else {
                // Style Inactive
                indicator.classList.add('opacity-50');
                circle.className =
                    "inline-flex items-center justify-center w-5 h-5 rounded-full border border-slate-300 dark:border-slate-600 text-[10px] text-slate-500";
                text.className = "text-slate-500 dark:text-slate-400";
            }
        }
    }

    function backToEmail() {
        showStep(1);
    }

    // === 4. OTP INPUT LOGIC (BEST UX) ===
    document.addEventListener("DOMContentLoaded", function() {
        const otpInputs = document.querySelectorAll(".otp-input");

        otpInputs.forEach((input, index) => {
            // Handle Type (Angka & Auto Next)
            input.addEventListener("input", (e) => {
                // Hapus karakter non-angka
                input.value = input.value.replace(/[^0-9]/g, '');

                if (input.value.length === 1) {
                    // Pindah ke next input jika ada
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                }
            });

            // Handle Backspace (Pindah ke prev input)
            input.addEventListener("keydown", (e) => {
                if (e.key === "Backspace" && input.value === "") {
                    if (index > 0) {
                        otpInputs[index - 1].focus();
                    }
                }
            });

            // Handle Paste (Isi semua sekaligus)
            input.addEventListener("paste", (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData("text").replace(/[^0-9]/g,
                    ''); // Ambil hanya angka

                if (pasteData) {
                    otpInputs.forEach((inp, i) => {
                        if (pasteData[i]) {
                            inp.value = pasteData[i];
                            // Fokus ke input terakhir yang terisi
                            if (i < otpInputs.length - 1) otpInputs[i + 1].focus();
                        }
                    });
                }
            });
        });
    });

    async function sendOTP() {
        const email = document.getElementById('resetEmail').value;
        if (!email) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Email tidak boleh kosong.'
            });
            return;
        }

        try {
            // Tampilkan Loading
            Swal.fire({
                title: 'Mengirim...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const res = await fetch("{{ route('forgot.send-otp') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email
                }),
            });
            const data = await res.json();

            if (!res.ok || !data.success) {
                // GANTI ALERT ERROR
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Gagal mengirim OTP.'
                });
                return;
            }

            // GANTI ALERT SUKSES
            Swal.fire({
                icon: 'success',
                title: 'Terkirim!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });

            showStep(2);
            setTimeout(() => document.getElementById('otp1').focus(), 100);

        } catch (e) {
            console.error(e);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem.'
            });
        }
    }

    async function verifyOTP() {
        const email = document.getElementById('resetEmail').value;
        let otp = '';

        // Gabungkan value dari 6 input
        for (let i = 1; i <= 6; i++) {
            otp += document.getElementById('otp' + i).value;
        }

        // Validasi input
        if (otp.length !== 6) {
            Swal.fire({
                icon: 'warning',
                title: 'Kode tidak lengkap',
                text: 'Kode OTP harus 6 digit.',
                confirmButtonColor: '#a855f7' // warna purple-500
            });
            return;
        }

        try {
            // Tampilkan Loading
            Swal.fire({
                title: 'Memeriksa Kode...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const res = await fetch("{{ route('forgot.verify-otp') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    email,
                    otp
                }),
            });

            const data = await res.json();

            // Jika Gagal
            if (!res.ok || !data.success) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'OTP tidak valid.',
                    confirmButtonColor: '#ef4444' // red-500
                });
                return;
            }

            // Jika Sukses (Tutup loading, tampilkan sukses sebentar, lalu lanjut step)
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                timer: 1500, // Otomatis tutup dalam 1.5 detik
                showConfirmButton: false
            });

            // Lanjut ke Step 3
            showStep(3);

            // Simpan OTP di variabel global agar bisa dipakai saat submit password
            window.__lastOtp = otp;

        } catch (e) {
            console.error(e);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem. Silakan coba lagi.',
            });
        }
    }

    async function saveNewPassword() {
        const email = document.getElementById('resetEmail').value;
        const otp = window.__lastOtp; // Mengambil OTP dari step sebelumnya
        const password = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        // Validasi Password Kosong / Pendek
        if (!password || password.length < 8) {
            Swal.fire({
                icon: 'warning',
                title: 'Password Lemah',
                text: 'Password minimal 8 karakter.',
                confirmButtonColor: '#a855f7'
            });
            return;
        }

        // Validasi Kecocokan Password
        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Cocok',
                text: 'Konfirmasi password tidak sama dengan password baru.',
                confirmButtonColor: '#a855f7'
            });
            return;
        }

        try {
            // Tampilkan Loading
            Swal.fire({
                title: 'Menyimpan...',
                html: 'Sedang memperbarui password Anda',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const res = await fetch("{{ route('forgot.reset-by-otp') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    email,
                    otp,
                    password,
                    password_confirmation: confirmPassword
                }),
            });

            const data = await res.json();

            // Jika Gagal
            if (!res.ok || !data.success) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Reset',
                    text: data.message || 'Gagal mengubah password.',
                    confirmButtonColor: '#ef4444'
                });
                return;
            }

            // Jika Sukses
            Swal.fire({
                icon: 'success',
                title: 'Selesai!',
                text: 'Password berhasil direset. Silakan login kembali.',
                confirmButtonColor: '#a855f7',
                confirmButtonText: 'Oke, Siap Login'
            }).then((result) => {
                // Kode ini jalan setelah user klik tombol OK di SweetAlert
                closeForgotModal();
                window.location.reload(); // Reload halaman untuk membersihkan state
            });

        } catch (e) {
            console.error(e);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem. Cek koneksi Anda.',
            });
        }
    }
</script>
