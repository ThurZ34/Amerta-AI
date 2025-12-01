<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Amerta AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    @livewireStyles
    <style>
        /* Custom Scrollbar untuk Chat Area */
        .chat-scroll::-webkit-scrollbar { width: 6px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background-color: #4f46e5; border-radius: 20px; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 font-sans antialiased overflow-hidden">

    <div class="flex h-screen w-full">

        <aside class="w-64 bg-gray-950 border-r border-gray-800 flex flex-col justify-between hidden md:flex">
            <div class="p-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">Amerta<span class="text-indigo-500">.AI</span></span>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-4">
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-indigo-600/10 text-indigo-400 rounded-xl border border-indigo-600/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    <span class="font-medium">Dashboard Chat</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-gray-200 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    <span class="font-medium">Laporan Keuangan</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-gray-200 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                    <span class="font-medium">Pengaturan</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->business->nama_bisnis ?? 'Owner' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col bg-gray-900 relative">

            @livewire('dashboard-chat')

        </main>

        <aside class="w-80 bg-gray-950 border-l border-gray-800 p-6 hidden lg:flex flex-col gap-6">

            <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-semibold text-gray-300">Kesehatan Bisnis</h3>
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                </div>

                <div class="mb-2 flex justify-between text-xs text-gray-400">
                    <span>Performance</span>
                    <span class="text-green-400 font-bold">85%</span>
                </div>
                <div class="w-full bg-gray-800 rounded-full h-2.5 mb-4">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-400 h-2.5 rounded-full" style="width: 85%"></div>
                </div>
                <p class="text-xs text-gray-500">Omset bulan ini stabil, tapi perhatikan stok bahan baku.</p>
            </div>

            <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-300 mb-4">Ringkasan Hari Ini</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-green-500/10 text-green-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                            </div>
                            <span class="text-sm text-gray-400">Pemasukan</span>
                        </div>
                        <span class="text-sm font-bold text-white">Rp 1.2jt</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-red-500/10 text-red-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                            </div>
                            <span class="text-sm text-gray-400">Pengeluaran</span>
                        </div>
                        <span class="text-sm font-bold text-white">Rp 450rb</span>
                    </div>
                </div>
            </div>

            <div class="flex-1 bg-gray-900 p-5 rounded-2xl border border-gray-800 shadow-sm flex flex-col">
                <h3 class="text-sm font-semibold text-gray-300 mb-4">Action Items</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-700 bg-gray-800 text-indigo-600 focus:ring-indigo-500/30">
                        <span class="text-sm text-gray-400 group-hover:text-gray-200 transition-colors">Cek stok opname</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-700 bg-gray-800 text-indigo-600 focus:ring-indigo-500/30">
                        <span class="text-sm text-gray-400 group-hover:text-gray-200 transition-colors">Posting Instagram story</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-700 bg-gray-800 text-indigo-600 focus:ring-indigo-500/30">
                        <span class="text-sm text-gray-400 group-hover:text-gray-200 transition-colors">Rekap bon belanja</span>
                    </label>
                </div>

                <div class="mt-auto pt-4 border-t border-gray-800">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-4 text-center">
                        <p class="text-xs font-medium text-white/90 mb-2">Upgrade ke PRO?</p>
                        <button class="text-xs bg-white text-indigo-600 px-3 py-1.5 rounded-lg font-bold w-full hover:bg-gray-100 transition">Lihat Paket</button>
                    </div>
                </div>
            </div>

        </aside>

    </div>

    @livewireScripts
</body>
</html>
