<?php

use App\Http\Controllers\Admin\BlacklistController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\KeywordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------
// Public Routes
// ---------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
});

// ---------------------------------------------------------
// Admin Panel Routes (Protected by Auth/Admin Middleware)
// ---------------------------------------------------------
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Categories Management
    Route::prefix('categories')->name('categories.')->group(function () {
        // Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::get('/trash', [CategoryController::class, 'trash'])->name('trash');
        Route::post('/{id}/restore', [CategoryController::class, 'restore'])->name('restore');
        });
        Route::resource('categories', CategoryController::class);
        
    // Keywords Management
    Route::prefix('keywords')->name('keywords.')->group(function () {
        Route::get('/search', [KeywordController::class, 'search'])->name('search');
        Route::get('/review', [KeywordController::class, 'review'])->name('review');
        Route::post('/merge', [KeywordController::class, 'merge'])->name('merge');
        Route::post('/{id}/restore', [KeywordController::class, 'restore'])->name('restore');
        Route::post('/bulk-update', [KeywordController::class, 'bulkUpdate'])->name('bulk-update');
        Route::post('/{keyword}/toggle-status', [KeywordController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('/{keyword}/update-status', [KeywordController::class, 'updateStatus'])->name('updateStatus');
    });
    Route::resource('keywords', KeywordController::class);

    // Blacklist Management
    Route::prefix('blacklists')->name('blacklists.')->group(function () {
        Route::post('/cleanup', [BlacklistController::class, 'bulkCleanup'])->name('cleanup');
    });
    Route::resource('blacklists', BlacklistController::class)->only(['index', 'store', 'destroy']);

});

// ---------------------------------------------------------
// User Profile & Dashboard (Standard Auth)
// ---------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

require __DIR__.'/auth.php';