<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Amerta AI</title>
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    @livewireStyles
    <style>
        .chat-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .chat-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background-color: #4f46e5;
            border-radius: 20px;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased overflow-hidden">

    <div class="flex h-screen w-full">

        <!-- 1. PANGGIL SIDEBAR YANG SUDAH DIPISAH DI SINI -->
        @include('layouts.partials.sidebar')

        <!-- MAIN CONTENT (CHAT AREA) -->
        <main class="flex-1 flex flex-col bg-gray-900 relative min-w-0">
            <!-- Asumsi livewire component ini handle chat area -->
            @livewire('dashboard-chat')
        </main>

        <!-- RIGHT SIDEBAR (WIDGETS) - Tetap di sini karena spesifik untuk dashboard -->
        {{-- <aside class="w-80 bg-gray-950 border-l border-gray-800 p-6 hidden lg:flex flex-col gap-6 overflow-y-auto">

            <!-- Widget Kesehatan Bisnis -->
            <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-semibold text-gray-300">Kesehatan Bisnis</h3>
                    <span class="flex h-3 w-3 relative">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                </div>

                <div class="mb-2 flex justify-between text-xs text-gray-400">
                    <span>Performance</span>
                    <span class="text-green-400 font-bold">85%</span>
                </div>
                <div class="w-full bg-gray-800 rounded-full h-2.5 mb-4">
                    <div class="bg-linear-to-r from-green-500 to-emerald-400 h-2.5 rounded-full" style="width: 85%">
                    </div>
                </div>
                <p class="text-xs text-gray-500">Omset bulan ini stabil, tapi perhatikan stok bahan baku.</p>
            </div>

            <!-- Widget Ringkasan -->
            <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-300 mb-4">Ringkasan Hari Ini</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-green-500/10 text-green-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-400">Pemasukan</span>
                        </div>
                        <span class="text-sm font-bold text-white">Rp 1.2jt</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-red-500/10 text-red-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-400">Pengeluaran</span>
                        </div>
                        <span class="text-sm font-bold text-white">Rp 450rb</span>
                    </div>
                </div>
            </div>

            <!-- Widget Action Items -->
            <div class="flex-1 bg-gray-900 p-5 rounded-2xl border border-gray-800 shadow-sm flex flex-col">
                <h3 class="text-sm font-semibold text-gray-300 mb-4">Action Items</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox"
                            class="w-4 h-4 rounded border-gray-700 bg-gray-800 text-indigo-600 focus:ring-indigo-500/30">
                        <span class="text-sm text-gray-400 group-hover:text-gray-200 transition-colors">Cek stok
                            opname</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox"
                            class="w-4 h-4 rounded border-gray-700 bg-gray-800 text-indigo-600 focus:ring-indigo-500/30">
                        <span class="text-sm text-gray-400 group-hover:text-gray-200 transition-colors">Posting
                            Instagram story</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox"
                            class="w-4 h-4 rounded border-gray-700 bg-gray-800 text-indigo-600 focus:ring-indigo-500/30">
                        <span class="text-sm text-gray-400 group-hover:text-gray-200 transition-colors">Rekap bon
                            belanja</span>
                    </label>
                </div>

                <div class="mt-auto pt-4 border-t border-gray-800">
                    <div class="bg-linear-to-r from-indigo-600 to-purple-600 rounded-xl p-4 text-center">
                        <p class="text-xs font-medium text-white/90 mb-2">Upgrade ke PRO?</p>
                        <button
                            class="text-xs bg-white text-indigo-600 px-3 py-1.5 rounded-lg font-bold w-full hover:bg-gray-100 transition">Lihat
                            Paket</button>
                    </div>
                </div>
            </div>
        </aside>

    </div> --}}

    @livewireScripts
</body>

</html>
