@extends('auth.layout')

@section('title', 'Login')

@section('image_url', asset('images/banner_login.png'))
@section('content')

    <div class="text-left mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Login to Continue</h1>
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

        {{-- PASSWORD --}}
        <div>


            <div class="flex items-center gap-3 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-3 bg-white dark:bg-gray-800">
                <img src="{{ asset('images/password.png')}}" class="w-6 h-6 opacity-70">
                <input type="password" name="password" required
                    class="flex-1 outline-none bg-transparent dark:text-white placeholder-gray-400"
                    placeholder="Password">
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

    {{-- Teks bawah --}}
    <p class="text-center text-gray-600 dark:text-gray-400 text-sm mt-4">
        By continuing, you agree to the
        <span class="font-medium text-black dark:text-white">Terms of use</span>
        and
        <span class="font-medium text-black dark:text-white">Privacy Policy.</span>
    </p>



@endsection
{{-- ============================
      POPUP FORGOT PASSWORD (3 STEP)
============================= --}}
<div id="forgotModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-xl p-6 shadow-xl">

        {{-- STEP 1: MASUKKAN EMAIL --}}
        <div id="stepEmail">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-3">
                Reset Password
            </h2>

            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Masukkan email anda untuk menerima kode OTP.
            </p>

            <input type="email" id="resetEmail"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg 
                bg-white dark:bg-gray-800 dark:text-white outline-none"
                placeholder="Masukkan email anda">

            <button onclick="sendOTP()"
                class="w-full bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 rounded-lg mt-4">
                Kirim Kode OTP
            </button>

            <button onclick="closeForgotModal()"
                class="text-center w-full mt-3 text-sm text-gray-500 hover:text-gray-700">
                Batal
            </button>
        </div>


        {{-- STEP 2: MASUKKAN OTP --}}
        <div id="stepOTP" class="hidden text-center">

            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-3">
                Masukkan Kode OTP
            </h2>

            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Masukkan 6 digit kode yang telah kami kirim ke email anda.
            </p>

            <div class="flex justify-center gap-3 mb-4">
                @for ($i = 1; $i <= 6; $i++)
                    <input id="otp{{ $i }}" maxlength="1"
                        class="w-10 h-12 border border-gray-300 dark:border-gray-700 rounded-lg text-center text-xl
                        bg-white dark:bg-gray-800 dark:text-white outline-none focus:ring-2 focus:ring-purple-500"
                        oninput="moveToNext(this, {{ $i }})">
                @endfor
            </div>

            <button onclick="verifyOTP()"
                class="w-full bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 rounded-lg">
                Verifikasi
            </button>

            <button onclick="backToEmail()"
                class="text-center w-full mt-3 text-sm text-gray-500 hover:text-gray-700">
                Kembali
            </button>
        </div>


        {{-- STEP 3: RESET PASSWORD BARU --}}
        <div id="stepReset" class="hidden">

            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-3">
                Buat Password Baru
            </h2>

            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Masukkan password baru anda, lalu konfirmasi kembali.
            </p>

            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Password Baru
                    </label>
                    <input type="password" id="newPassword"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg 
                        bg-white dark:bg-gray-800 dark:text-white outline-none"
                        placeholder="Minimal 8 karakter">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Ulangi Password Baru
                    </label>
                    <input type="password" id="confirmPassword"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg 
                        bg-white dark:bg-gray-800 dark:text-white outline-none"
                        placeholder="Ketik ulang password">
                </div>
            </div>

            <button onclick="saveNewPassword()"
                class="w-full bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 rounded-lg mt-4">
                Simpan Password
            </button>

            <button onclick="closeForgotModal()"
                class="text-center w-full mt-3 text-sm text-gray-500 hover:text-gray-700">
                Batal
            </button>
        </div>

    </div>
</div>


<script>
    function openForgotModal() {
        document.getElementById('forgotModal').classList.remove('hidden');
        document.getElementById('forgotModal').classList.add('flex');

        // pastikan mulai dari step email
        document.getElementById('stepEmail').classList.remove('hidden');
        document.getElementById('stepOTP').classList.add('hidden');
        document.getElementById('stepReset').classList.add('hidden');
    }

    function closeForgotModal() {
        document.getElementById('forgotModal').classList.add('hidden');
        document.getElementById('forgotModal').classList.remove('flex');
    }

    function sendOTP() {
        // TODO: Kirim request ke backend untuk kirim OTP ke email
        // Sementara langsung lanjut ke step OTP
        document.getElementById('stepEmail').classList.add('hidden');
        document.getElementById('stepOTP').classList.remove('hidden');
    }

    function backToEmail() {
        document.getElementById('stepOTP').classList.add('hidden');
        document.getElementById('stepEmail').classList.remove('hidden');
    }

    // Auto move next for OTP input
    function moveToNext(field, index) {
        if (field.value.length === 1 && index < 6) {
            document.getElementById("otp" + (index + 1)).focus();
        }
    }

    function verifyOTP() {
        // TODO: Verifikasi OTP ke backend
        // Kalau valid, lanjut ke step reset password
        document.getElementById('stepOTP').classList.add('hidden');
        document.getElementById('stepReset').classList.remove('hidden');
    }

    function saveNewPassword() {
        const pass = document.getElementById('newPassword').value;
        const confirm = document.getElementById('confirmPassword').value;

        if (!pass || pass.length < 8) {
            alert('Password minimal 8 karakter.');
            return;
        }

        if (pass !== confirm) {
            alert('Konfirmasi password tidak sama.');
            return;
        }

        // TODO: Kirim password baru + OTP/email ke backend untuk update password
        alert('Password berhasil diubah (simulasi). Nanti dihubungkan ke backend.');

        closeForgotModal();
    }
</script>
