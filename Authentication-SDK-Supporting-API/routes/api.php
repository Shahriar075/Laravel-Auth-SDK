<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/add-user-role', [AuthController::class, 'addUserRole'])->name('add-user-role');

Route::post('auth/refresh', [AuthController::class, 'refreshToken']);
Route::post('oauth/refresh', [OAuthController::class, 'refreshToken']);

Route::middleware('web')->group(function () {
    Route::get('auth/{provider}', [OAuthController::class, 'redirectToProvider']);
    Route::get('auth/{provider}/callback', [OAuthController::class, 'handleProviderCallback']);
});
