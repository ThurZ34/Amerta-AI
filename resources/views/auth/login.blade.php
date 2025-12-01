<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Font Google (Opsional: Inter) */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white">

    <div class="flex min-h-screen">
        
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white text-gray-800">
            <div class="w-full max-w-md space-y-6">
                
                <div class="text-left">
                    <h1 class="text-3xl font-bold mb-2">Selamat Datang Kembali</h1>
                    <p class="text-gray-500">Silakan masukkan detail akun anda.</p>
                </div>

                <button class="w-full flex items-center justify-center gap-3 border border-gray-300 rounded-lg p-3 hover:bg-gray-50 transition">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                    <span class="text-sm font-medium text-gray-700">Masuk dengan Google</span>
                </button>

                <div class="relative flex py-2 items-center">
                    <div class="grow border-t border-gray-200"></div>
                    <span class="shrink-0 mx-4 text-gray-400 text-sm">atau dengan email</span>
                    <div class="grow border-t border-gray-200"></div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                            placeholder="nama@email.com">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-gray-600 hover:text-black">Lupa Password?</a>
                            @endif
                        </div>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent outline-none transition"
                            placeholder="••••••••">
                    </div>

                    <button type="submit" 
                        class="w-full bg-black text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition duration-300">
                        Masuk
                    </button>
                </form>

                <div class="text-center text-sm text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-semibold text-black hover:underline">Daftar Sekarang</a>
                </div>

            </div>
        </div>

        <div class="hidden md:block w-1/2 bg-cover bg-center relative" 
             style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
            <div class="absolute inset-0 bg-black bg-opacity-10"></div>
        </div>

    </div>
</body>
</html>