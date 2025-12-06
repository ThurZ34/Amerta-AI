<?php

use App\Http\Controllers\DailyCheckinController;
use App\Http\Controllers\DashboardSelectionController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\MainMenuController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SurveyController;
use App\Livewire\Dashboard;
use App\Livewire\MarketingTools;
use Illuminate\Support\Facades\Route;

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
    });

    Route::controller(ProfilController::class)->group(function () {
        Route::get('/profil_bisnis', 'bussiness_index')->name('profil_bisnis');
        Route::put('/profil_bisnis', 'update')->name('profil_bisnis.update');
        Route::post('/categories', 'storeCategory')->name('categories.store');
    });

    Route::post('/riwayat/scan', [RiwayatController::class, 'scan'])->name('riwayat.scan');
    Route::resource('riwayat', RiwayatController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::middleware(['ensure.business.complete'])->group(function () {

        Route::post('/setup-bisnis', [SurveyController::class, 'store'])->name('setup-bisnis.store');

        Route::resource('daily-checkin', DailyCheckinController::class)->except(['destroy']);

        Route::get('/amerta', fn () => view('amerta'))->name('amerta');

        Route::get('/marketing-tools', MarketingTools::class)->name('marketing-tools');

        Route::controller(MainMenuController::class)->prefix('main_menu')->group(function () {
            Route::get('/', 'index')->name('main_menu');
            Route::post('/update-target', 'updateTarget')->name('main_menu.update-target');
        });

        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        Route::post('/produk/suggest-price', [ProdukController::class, 'suggestPrice'])->name('produk.suggest-price');
        Route::resource('produk', ProdukController::class);

    });
});
