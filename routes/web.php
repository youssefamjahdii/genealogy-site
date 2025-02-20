<?php

use App\Http\Controllers\PersonController;

Route::middleware(['auth'])->group(function () {
    Route::resource('people', PersonController::class);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
