<?php

use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;

Route::controller(UserAuthController::class)->prefix('user/')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login')->name('login');
    Route::get('logout', 'logout')->middleware('auth:sanctum'); //todo: deletable
});


Route::post('account/card/money/transfer', [\App\Http\Controllers\CardController::class, 'transfer'])->middleware('auth:sanctum');