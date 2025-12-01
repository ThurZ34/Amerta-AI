@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h2 class="text-2xl font-bold mb-4">Welcome back, {{ Auth::user()->name ?? 'User' }}!</h2>
            <p class="text-gray-600">You are logged in!</p>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stat Card 1 -->
                <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-indigo-100 rounded-lg text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900">1,234</p>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-emerald-100 rounded-lg text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Revenue</p>
                            <p class="text-2xl font-bold text-gray-900">$12,345</p>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-amber-50 p-6 rounded-xl border border-amber-100">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-amber-100 rounded-lg text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Active Tasks</p>
                            <p class="text-2xl font-bold text-gray-900">42</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
