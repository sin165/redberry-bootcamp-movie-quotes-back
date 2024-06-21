<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\PreventAuthenticatedAccess;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->middleware(SetLocale::class)->group(function () {
	Route::post('/register', 'register')->name('register');
	Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware(['signed'])->name('verification.verify');
	Route::post('/login', 'login')->middleware(PreventAuthenticatedAccess::class)->name('login');
	Route::get('/user', 'getCurrentUser')->middleware('auth:sanctum')->name('me');
	Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
	Route::post('/email/verification-notification', 'resendEmail')->middleware('throttle:6,1')->name('verification.send');
	Route::post('/forgot-password', 'sendResetLink')->name('password.email');
	Route::post('/reset-password', 'resetPassword')->name('password.update');
});

Route::controller(OAuthController::class)->group(function () {
	Route::get('/google/redirect', 'redirectToGoogle')->name('google.redirect');
	Route::get('/google/callback', 'handleGoogleCallback')->name('google.callback');
});

Route::post('/profile', [ProfileController::class, 'update'])->middleware([SetLocale::class, 'auth:sanctum'])->name('profile.update');

Route::controller(MovieController::class)->middleware(SetLocale::class)->group(function () {
	Route::get('/movies', 'index')->middleware('auth:sanctum')->name('movies.index');
	Route::post('/movies', 'store')->middleware('auth:sanctum')->name('movies.store');
	Route::get('/movies/{movie}', 'show')->middleware('auth:sanctum')->name('movies.show');
	Route::patch('/movies/{movie}', 'update')->middleware('auth:sanctum')->can('update', 'movie')->name('movies.update');
	Route::delete('/movies/{movie}', 'destroy')->middleware('auth:sanctum')->can('delete', 'movie')->name('movies.destroy');
});
