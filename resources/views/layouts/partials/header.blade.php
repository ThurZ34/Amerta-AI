<header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
    <!-- Left: Mobile Toggle & Title -->
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen"
            class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <h1 class="text-xl font-semibold text-gray-800">
            @yield('header', 'Dashboard')
        </h1>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-4">
        <!-- Notifications -->
        <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors relative">
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
        </button>

        <!-- Profile Dropdown (Simple implementation) -->
        <div class="relative ml-3" x-data="{ open: false }">
            <!-- Using a simple form for logout for now as a placeholder for a full dropdown -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 font-medium">
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</header>
