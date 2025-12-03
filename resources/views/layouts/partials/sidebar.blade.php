<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-950 border-r border-gray-200 dark:border-gray-800 flex flex-col transition-transform duration-300 transform md:relative md:translate-x-0 shrink-0">

    {{-- 1. BRAND LOGO --}}
    <div class="h-20 flex items-center px-6 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950">
        <a href="{{ route('main_menu') }}" class="flex items-center gap-3 w-full group">
            {{-- Icon Container --}}
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            {{-- Text Container (Fixed Contrast) --}}
            <div class="flex flex-col">
                <span class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-none">
                    Amerta<span class="text-indigo-600 dark:text-indigo-400">.AI</span>
                </span>
                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-1">UMKM Assistant</span>
            </div>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar bg-white dark:bg-gray-950">

        <p class="px-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Menu Utama</p>

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
            {{ request()->routeIs('dashboard')
                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600 dark:text-white'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">

            <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-indigo-700 dark:text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-white' }} transition-colors"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H14a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H14a2 2 0 01-2-2v-2z" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('produk.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
            {{ request()->routeIs('produk.index')
                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600 dark:text-white'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('produk.index') ? 'text-indigo-700 dark:text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-white' }} transition-colors"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <span>Katalog Produk</span>
        </a>

        <a href="{{ route('daily-checkin.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
            {{ request()->routeIs('daily-checkin.index')
                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600 dark:text-white'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('daily-checkin.index') ? 'text-indigo-700 dark:text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-white' }} transition-colors"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span>Laporan Harian</span>
        </a>

        <a href="{{ route('expenses.create') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
            {{ request()->routeIs('expenses.create')
                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600 dark:text-white'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('expenses.create') ? 'text-indigo-700 dark:text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-white' }} transition-colors"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Catat Pengeluaran</span>
        </a>

        <a href="{{ route('riwayat.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
            {{ request()->routeIs('riwayat.index')
                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600 dark:text-white'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('riwayat.index') ? 'text-indigo-700 dark:text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-white' }} transition-colors"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
            </svg>
            <span>Riwayat Transaksi</span>
        </a>
        

    </div>

    <div class="p-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900">
        <a href="{{ route('main_menu') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors mt-2 group">
            <div class="w-5 h-5 flex items-center justify-center rounded-md bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400 group-hover:bg-gray-300 dark:group-hover:bg-gray-700 transition-colors">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </div>
            <span>Kembali ke Menu</span>
        </a>
    </div>
</aside>
