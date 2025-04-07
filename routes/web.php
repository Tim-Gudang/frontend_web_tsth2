<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatuanController;
use Illuminate\Support\Facades\Route;

// Public routes (accessible without authentication)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'Login'])->name('login.post');
});

// Authenticated routes
Route::middleware(['auth.api'])->group(function () {

    // Authentication routes
    Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Scan result
    Route::get('/scan-result', function () {
        $data = request()->query('data');
        return view('scan-result', compact('data'));
    })->name('scan.result');

    // User profile
    Route::get('/user_profile', function () {
        return view('profile.user_profile');
    })->name('user.profile');

    // Resource controllers
    Route::resource('barangs', BarangController::class);
    Route::resource('satuans', SatuanController::class);
});
// Fallback route
Route::fallback(function () {
    return view('error.error');
})->name('fallback');
