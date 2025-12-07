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
            <div class="flex items-center gap-3 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-3 bg-white dark:bg-gray-800">
                <img src="{{ asset('images/email.png')}}" class="w-6 h-6 opacity-70">
                <input autocomplete="off" type="email" name="email" required autofocus
                    class="flex-1 outline-none bg-transparent dark:text-white placeholder-gray-400"
                    placeholder="Email">
            </div>
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- PASSWORD + SHOW/HIDE --}}
        <div>
            <div class="relative flex items-center gap-3 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-3 bg-white dark:bg-gray-800">
                <img src="{{ asset('images/password.png')}}" class="w-6 h-6 opacity-70">

                {{-- tambahkan id + padding kanan buat ruang icon --}}
                <input
                    id="loginPassword"
                    type="password"
                    name="password"
                    required
                    class="flex-1 outline-none bg-transparent dark:text-white placeholder-gray-400 pr-10"
                    placeholder="Password">

                {{-- tombol show/hide password --}}
                <button
                    type="button"
                    onclick="toggleLoginPassword()"
                    class="absolute right-3 p-1 text-gray-400 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">

                    {{-- icon eye (show) --}}
                    <svg id="iconPasswordShow" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24" fill="none"
                         class="w-5 h-5">
                        <path d="M2 12C3.8 7.8 7.5 5 12 5C16.5 5 20.2 7.8 22 12C20.2 16.2 16.5 19 12 19C7.5 19 3.8 16.2 2 12Z"
                              stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/>
                    </svg>

                    {{-- icon eye-off (hide) --}}
                    <svg id="iconPasswordHide" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24" fill="none"
                         class="w-5 h-5 hidden">
                        <path d="M3 3L21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        <path d="M10.6 10.6C10.2 11 10 11.5 10 12C10 13.1 10.9 14 12 14C12.5 14 13 13.8 13.4 13.4"
                              stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.4 7.5C5.5 8.4 3.9 10 3 12C4.8 16.2 8.5 19 13 19C14.3 19 15.6 18.7 16.7 18.2"
                              stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9.5 5.1C10.3 5 11.1 5 12 5C16.5 5 20.2 7.8 22 12C21.6 13 21.1 13.9 20.4 14.7"
                              stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex justify-between items-center mb-1">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300"></span>
            @if (Route::has('password.request'))
                <button type="button"
                    onclick="openForgotModal()"
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
<script>
    // === SHOW / HIDE PASSWORD LOGIN ===
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

    function openForgotModal() {
        const modal = document.getElementById('forgotModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // default: tampilkan step email
        document.getElementById('stepEmail').classList.remove('hidden');
        document.getElementById('stepOTP').classList.add('hidden');
        document.getElementById('stepReset').classList.add('hidden');

        // KUNCI SCROLL BODY
        document.body.classList.add('overflow-hidden');
    }

    function closeForgotModal() {
        const modal = document.getElementById('forgotModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        // LEPAS KUNCI SCROLL BODY
        document.body.classList.remove('overflow-hidden');
    }

    function backToEmail() {
        document.getElementById('stepOTP').classList.add('hidden');
        document.getElementById('stepReset').classList.add('hidden');
        document.getElementById('stepEmail').classList.remove('hidden');
    }

    function moveToNext(field, index) {
        if (field.value.length === 1 && index < 6) {
            document.getElementById('otp' + (index + 1)).focus();
        }
    }

    async function sendOTP() {
        const email = document.getElementById('resetEmail').value;

        if (!email) {
            alert('Email tidak boleh kosong.');
            return;
        }

        try {
            const res = await fetch("{{ route('forgot.send-otp') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ email }),
            });

            const data = await res.json();

            if (!res.ok || !data.success) {
                alert(data.message || 'Gagal mengirim OTP.');
                return;
            }

            alert(data.message);

            document.getElementById('stepEmail').classList.add('hidden');
            document.getElementById('stepOTP').classList.remove('hidden');

        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan. Coba lagi nanti.');
        }
    }

    async function verifyOTP() {
        const email = document.getElementById('resetEmail').value;
        let otp = '';

        for (let i = 1; i <= 6; i++) {
            otp += document.getElementById('otp' + i).value;
        }

        if (otp.length !== 6) {
            alert('Kode OTP harus 6 digit.');
            return;
        }

        try {
            const res = await fetch("{{ route('forgot.verify-otp') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ email, otp }),
            });

            const data = await res.json();

            if (!res.ok || !data.success) {
                alert(data.message || 'OTP tidak valid.');
                return;
            }

            alert(data.message);

            document.getElementById('stepOTP').classList.add('hidden');
            document.getElementById('stepReset').classList.remove('hidden');

            window.__lastOtp = otp;

        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan. Coba lagi.');
        }
    }

    async function saveNewPassword() {
        const email = document.getElementById('resetEmail').value;
        const otp = window.__lastOtp;
        const password = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (!password || password.length < 8) {
            alert('Password minimal 8 karakter.');
            return;
        }

        if (password !== confirmPassword) {
            alert('Konfirmasi password tidak sama.');
            return;
        }

        try {
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

            if (!res.ok || !data.success) {
                alert(data.message || 'Gagal mengubah password.');
                return;
            }

            alert(data.message);
            closeForgotModal();

        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan. Coba lagi.');
        }
    }
</script>
