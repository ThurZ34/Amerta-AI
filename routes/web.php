<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SurveyController;

Route::get('/', function () {
    return view('landing_page');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/setup-bisnis', [SurveyController::class, 'index'])->name('setup-bisnis');
    Route::post('/setup-bisnis', [SurveyController::class, 'store'])->name('setup-bisnis.store');

    Route::middleware(['ensure.business.complete'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });
    Route::get('/finance', function () {
    return view('finance');
})->name('finance');
});
