<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SurveyController;

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
    // Rute Setup Bisnis (Harus di luar ensure.business.complete agar tidak infinite loop)
    Route::get('/setup-bisnis', [SurveyController::class, 'index'])->name('setup-bisnis');
    Route::post('/setup-bisnis', [SurveyController::class, 'store'])->name('setup-bisnis.store');

    Route::middleware(['ensure.business.complete'])->group(function () {

        Route::get('/amerta', function () {
            return view('amerta');
        })->name('amerta');

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::resource('produk', \App\Http\Controllers\ProdukController::class);

    });

    Route::get('/profil_bisnis', [\App\Http\Controllers\ProfilController::class, 'bussiness_index'])->name('profil_bisnis');
    Route::put('/profil_bisnis', [\App\Http\Controllers\ProfilController::class, 'update'])->name('profil_bisnis.update');
});
