<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatuanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.token')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'handleLogin'])->name('post.login')->middleware('guest');
});

Route::middleware('auth.session')->group(function () {
    Route::post('/logout', [AuthController::class, 'handleLogout'])->name('auth.logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/scan-result', function () {
        $data = request()->query('data');
        return view('scan-result', compact('data'));
    });

    Route::get('/user_profile', function () {
        return view('profile.user_profile');
    })->name('user_profile');

    Route::resource('barangs', BarangController::class);
    Route::resource('satuans', SatuanController::class);
});

Route::get('/error', function () {
    return view('error.error');
})->name('error');
