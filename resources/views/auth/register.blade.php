<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white">

    <div class="flex min-h-screen">

        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white text-gray-800">
            <div class="w-full max-w-md space-y-6">

                <div class="text-left">
                    <h1 class="text-3xl font-bold mb-2">Buat Akun Baru</h1>
                    <p class="text-gray-500">Mulai perjalanan anda bersama kami.</p>
                </div>

                <button class="w-full flex items-center justify-center gap-3 border border-gray-300 rounded-lg p-3 hover:bg-gray-50 transition">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                    <span class="text-sm font-medium text-gray-700">Daftar dengan Google</span>
                </button>

                <div class="relative flex py-2 items-center">
                    <div class="grow border-t border-gray-200"></div>
                    <span class="shrink-0 mx-4 text-gray-400 text-sm">atau dengan email</span>
                    <div class="grow border-t border-gray-200"></div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input autocomplete="off" type="text" name="name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                            placeholder="Masukkan Nama Anda">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input autocomplete="off" type="email" name="email" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                            placeholder="Masukkan Email Anda">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                            placeholder="Minimal 8 karakter">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ulangi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                            placeholder="Ketik ulang password">
                    </div>

                    <button type="submit"
                        class="w-full bg-black text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition duration-300">
                        Daftar
                    </button>
                </form>

                <div class="text-center text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-black hover:underline">Masuk disini</a>
                </div>

            </div>
        </div>

        <div class="hidden md:block w-1/2 bg-cover bg-center"
             style="background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
        </div>

    </div>
</body>
</html>
