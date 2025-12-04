<?php

use App\Http\Controllers\DailyCheckinController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\GoogleAuthController;
use App\Livewire\Dashboard;
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

Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/setup-bisnis', [SurveyController::class, 'index'])->name('setup-bisnis');

    Route::get('/dashboard-selection', [App\Http\Controllers\DashboardSelectionController::class, 'index'])->name('dashboard-selection');
    Route::post('/dashboard-selection/join', [App\Http\Controllers\DashboardSelectionController::class, 'join'])->name('dashboard-selection.join');
    Route::middleware(['ensure.business.complete'])->group(function () {

        Route::post('/setup-bisnis', [SurveyController::class, 'store'])->name('setup-bisnis.store');

        Route::get('/daily-checkin', [DailyCheckinController::class, 'index'])->name('daily-checkin.index');
        Route::get('/daily-checkin/create', [DailyCheckinController::class, 'create'])->name('daily-checkin.create');
        Route::post('/daily-checkin', [DailyCheckinController::class, 'store'])->name('daily-checkin.store');
        Route::get('/daily-checkin/{id}', [DailyCheckinController::class, 'show'])->name('daily-checkin.show');
        Route::get('/daily-checkin/{id}/edit', [DailyCheckinController::class, 'edit'])->name('daily-checkin.edit');
        Route::put('/daily-checkin/{id}', [DailyCheckinController::class, 'update'])->name('daily-checkin.update');

        Route::get('/amerta', function () {
            return view('amerta');
        })->name('amerta');

        Route::get('/main_menu', [\App\Http\Controllers\MainMenuController::class, 'index'])->name('main_menu');
        Route::post('/main_menu/update-target', [\App\Http\Controllers\MainMenuController::class, 'updateTarget'])->name('main_menu.update-target');

        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        Route::post('/produk/suggest-price', [ProdukController::class, 'suggestPrice'])->name('produk.suggest-price');
        Route::resource('produk', ProdukController::class);

    });

    Route::get('/profil_bisnis', [\App\Http\Controllers\ProfilController::class, 'bussiness_index'])->name('profil_bisnis');
    Route::put('/profil_bisnis', [\App\Http\Controllers\ProfilController::class, 'update'])->name('profil_bisnis.update');
    Route::post('/categories', [\App\Http\Controllers\ProfilController::class, 'storeCategory'])->name('categories.store');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::post('/riwayat/scan', [RiwayatController::class, 'scan'])->name('riwayat.scan');
    Route::post('/riwayat', [RiwayatController::class, 'store'])->name('riwayat.store');
    Route::put('/riwayat/{id}', [RiwayatController::class, 'update'])->name('riwayat.update');
    Route::delete('/riwayat/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');
});
