<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-950 border-r border-gray-200 dark:border-gray-800 flex flex-col transition-transform duration-300 transform md:relative md:translate-x-0 shrink-0">

    <div class="h-20 flex items-center px-6 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950">
        <a href="{{ route('main_menu') }}" class="flex items-center gap-3 w-full group">
            <div
                class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-none">
                    Amerta<span class="text-indigo-600 dark:text-indigo-400">.AI</span>
                </span>
                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-1">
                    UMKM Assistant
                </span>
            </div>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar bg-white dark:bg-gray-950">

        <p class="px-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2 mt-1">
            Analisis & Bantuan
        </p>

        <a href="{{ route('analisis.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
            {{ request()->routeIs('analisis.dashboard')
                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600 dark:text-white'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('analisis.dashboard') ? 'text-indigo-700 dark:text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-white' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H14a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H14a2 2 0 01-2-2v-2z" />
            </svg>
            <span>Dashboard</span>
        </a>

        <p class="px-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2 mt-4">
            Operasional Harian
        </p>

        <a href="{{ route('operasional.analisis-penjualan.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
            {{ request()->routeIs('operasional.analisis-penjualan.*')
                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600 dark:text-white'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('operasional.analisis-penjualan.*') ? 'text-indigo-700 dark:text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-white' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span>Analisis Penjualan</span>
        </a>

        <a href="{{ route('operasional.kasir') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
            {{ request()->routeIs('operasional.kasir')
                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600 dark:text-white'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('operasional.kasir') ? 'text-indigo-700 dark:text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-white' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z" />
            </svg>
            <span>Kasir</span>
        </a>

        <a href="{{ route('operasional.riwayat-keuangan.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
            {{ request()->routeIs('operasional.riwayat-keuangan.index')
                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600 dark:text-white'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('operasional.riwayat-keuangan.index') ? 'text-indigo-700 dark:text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-white' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
            </svg>
            <span>Riwayat Keuangan</span>
        </a>

        <p class="px-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2 mt-4">
            Manajemen Bisnis
        </p>

        <a href="{{ route('manajemen.produk.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
            {{ request()->routeIs('manajemen.produk.*')
                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600 dark:text-white'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('manajemen.produk.*') ? 'text-indigo-700 dark:text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-white' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <span>Katalog Produk</span>
        </a>

        <a href="{{ route('manajemen.profil-bisnis.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
            {{ request()->routeIs('manajemen.profil-bisnis.*')
                ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-600 dark:text-white'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('manajemen.profil-bisnis.*') ? 'text-indigo-700 dark:text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-white' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span>Profil Bisnis</span>
        </a>
    </div>

    <div class="p-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900">
        <a href="{{ route('main_menu') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors mt-2 group">
            <div
                class="w-5 h-5 flex items-center justify-center rounded-md bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400 group-hover:bg-gray-300 dark:group-hover:bg-gray-700 transition-colors">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </div>
            <span>Kembali ke Menu</span>
        </a>
    </div>

</aside>
