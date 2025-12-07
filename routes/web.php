<?php

use App\Http\Controllers\BusinessRequestController;
use App\Http\Controllers\DailyCheckinController;
use App\Http\Controllers\DashboardSelectionController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\MainMenuController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SurveyController;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;

Route::get('/', function () {
    return view('landing_page');
});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('lang.switch');

Route::middleware('guest')->controller(GoogleAuthController::class)->group(function () {
    Route::get('/auth/google', 'redirect')->name('google.login');
    Route::get('/auth/google/callback', 'callback');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/setup-bisnis', [SurveyController::class, 'index'])->name('setup-bisnis');

    Route::controller(DashboardSelectionController::class)->prefix('dashboard-selection')->group(function () {
        Route::get('/', 'index')->name('dashboard-selection');
        Route::post('/join', 'join')->name('dashboard-selection.join');
        Route::post('/dashboard-selection/cancel/{id}', 'cancelRequest')->name('dashboard-selection.cancel-request');
    });

    Route::middleware(['ensure.business.complete'])->group(function () {
        Route::post('/setup-bisnis', [SurveyController::class, 'store'])->name('setup-bisnis.store');

        Route::get('/amerta', fn() => view('amerta'))->name('amerta');

        Route::controller(MainMenuController::class)->prefix('main_menu')->group(function () {
            Route::get('/', 'index')->name('main_menu');
            Route::post('/update-target', 'updateTarget')->name('main_menu.update-target');
        });

        Route::controller(\App\Http\Controllers\UserProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('profile.edit');
            Route::put('/profile', 'update')->name('profile.update');
        });

        // ============================================
        // ANALISIS & BANTUAN
        // ============================================
        Route::prefix('analisis')->name('analisis.')->group(function () {
            Route::get('/dashboard', Dashboard::class)->name('dashboard');
        });

        // ============================================
        // OPERASIONAL HARIAN
        // ============================================
        Route::prefix('operasional')->name('operasional.')->group(function () {
            // Analisis Penjualan (Daily Checkin)
            Route::resource('analisis-penjualan', DailyCheckinController::class)
                ->except(['destroy'])
                ->parameters(['analisis-penjualan' => 'daily_checkin']);

            // Kasir
            Route::get('/kasir', [RiwayatController::class, 'kasir'])->name('kasir');

            // Riwayat Keuangan
            Route::post('/riwayat-keuangan/scan', [RiwayatController::class, 'scan'])->name('riwayat-keuangan.scan');
            Route::get('/riwayat-keuangan', [RiwayatController::class, 'index'])->name('riwayat-keuangan.index');
            Route::post('/riwayat-keuangan', [RiwayatController::class, 'store'])->name('riwayat-keuangan.store');
            Route::put('/riwayat-keuangan/{riwayat}', [RiwayatController::class, 'update'])->name('riwayat-keuangan.update');
            Route::delete('/riwayat-keuangan/{riwayat}', [RiwayatController::class, 'destroy'])->name('riwayat-keuangan.destroy');
        });

        // ============================================
        // MANAJEMEN BISNIS
        // ============================================
        Route::prefix('manajemen')->name('manajemen.')->group(function () {
            // Profil Bisnis
            Route::controller(ProfilController::class)->prefix('profil-bisnis')->name('profil-bisnis.')->group(function () {
                Route::get('/', 'bussiness_index')->name('index');
                Route::put('/', 'update')->name('update');
                Route::post('/initial-capital', 'updateInitialCapital')->name('update-initial-capital');
            });

            // Categories (used by Profil Bisnis)
            Route::post('/categories', [ProfilController::class, 'storeCategory'])->name('categories.store');

            // Business Join Requests
            Route::post('/business-request/{id}', [BusinessRequestController::class, 'action'])
                ->name('business-request.action');

            // Katalog Produk
            Route::post('/produk/analyze', [ProdukController::class, 'analyze'])->name('produk.analyze');
            Route::post('/produk/suggest-price', [ProdukController::class, 'suggestPrice'])->name('produk.suggest-price');
            Route::resource('produk', ProdukController::class);
        });
    });
});

Route::post('/forgot-password/send-otp', [ForgotPasswordOtpController::class, 'sendOtp'])
    ->name('forgot.send-otp');

Route::post('/forgot-password/verify-otp', [ForgotPasswordOtpController::class, 'verifyOtp'])
    ->name('forgot.verify-otp');

Route::post('/forgot-password/reset-by-otp', [ForgotPasswordOtpController::class, 'resetPassword'])
    ->name('forgot.reset-by-otp');
