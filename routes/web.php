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
    Route::get('/setup-bisnis', [SurveyController::class, 'index'])->name('setup-bisnis');
    Route::post('/setup-bisnis', [SurveyController::class, 'store'])->name('setup-bisnis.store');

    Route::middleware(['ensure.business.complete'])->group(function () {
        Route::get('/amerta', function () {
            return view('amerta');
        })->name('amerta');
    });
    Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
});
