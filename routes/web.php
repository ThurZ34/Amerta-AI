<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DailyCheckinController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Livewire\Dashboard;

Route::get('/', function () {
    return view('landing_page');
});
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

Route::middleware(['auth'])->group(function () {
    Route::get('/setup-bisnis', [SurveyController::class, 'index'])->name('setup-bisnis');
    Route::post('/setup-bisnis', [SurveyController::class, 'store'])->name('setup-bisnis.store');

    Route::get('/daily-checkin', [DailyCheckinController::class, 'index'])->name('daily-checkin.index');
Route::get('/daily-checkin/create', [DailyCheckinController::class, 'create'])->name('daily-checkin.create');
Route::post('/daily-checkin', [DailyCheckinController::class, 'store'])->name('daily-checkin.store');
Route::get('/daily-checkin/{id}', [DailyCheckinController::class, 'show'])->name('daily-checkin.show');
Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

    Route::middleware(['ensure.business.complete'])->group(function () {

        Route::get('/amerta', function () {
            return view('amerta');
        })->name('amerta');

        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        Route::post('/produk/suggest-price', [ProdukController::class, 'suggestPrice'])->name('produk.suggest-price');
        Route::resource('produk', ProdukController::class);

    });

    Route::get('/profil_bisnis', [\App\Http\Controllers\ProfilController::class, 'bussiness_index'])->name('profil_bisnis');
    Route::put('/profil_bisnis', [\App\Http\Controllers\ProfilController::class, 'update'])->name('profil_bisnis.update');
    Route::post('/categories', [\App\Http\Controllers\ProfilController::class, 'storeCategory'])->name('categories.store');
});
