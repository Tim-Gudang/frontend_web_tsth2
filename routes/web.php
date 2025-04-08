<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\BarangCategoryController;
use App\Http\Controllers\JenisBarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});
// Public routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected routes - Use middleware group
Route::middleware(['auth.api'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Resource controllers
    Route::resource('barangs', BarangController::class);
    Route::resource('satuans', SatuanController::class);
    Route::resource('transaction-types', TransactionTypeController::class)->except(['show']);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class); 
    Route::resource('jenis-barangs', JenisBarangController::class);    
   


    Route::resource('barang-categories', BarangCategoryController::class); // Fixed: Use resource routing

    Route::post('barangs/{id}/restore', [BarangController::class, 'restore'])->name('barangs.restore');
    // Additional TransactionType routes if needed
    Route::prefix('transaction-types')->group(function () {
        Route::get('/export', [TransactionTypeController::class, 'export'])->name('transaction-types.export');
        Route::post('/import', [TransactionTypeController::class, 'import'])->name('transaction-types.import');
    });

    Route::resource('gudangs', GudangController::class);
    Route::post('gudangs/{id}/restore', [GudangController::class, 'restore'])->name('gudangs.restore');
    Route::delete('gudangs/{id}/force', [GudangController::class, 'forceDelete'])->name('gudangs.force-delete');
});

// Logout route (accessible regardless of auth status)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Fallback route
Route::fallback(function () {
    return view('error.error');
})->name('fallback');
