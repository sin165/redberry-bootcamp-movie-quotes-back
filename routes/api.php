<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\PreventAuthenticatedAccess;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
	Route::post('/register', 'register')->name('register');
	Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware(['signed'])->name('verification.verify');
	Route::post('/login', 'login')->middleware(PreventAuthenticatedAccess::class)->name('login');
	Route::get('/user', 'getCurrentUser')->middleware('auth:sanctum')->name('me');
	Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
});
