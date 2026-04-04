<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KeywordReviewController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Keyword;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
});
Route::prefix('admin')->name('admin.')->group(function () {
    
    // الصفحة الرئيسية للداشبورد
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::resource('/categories', CategoryController::class);

    Route::get('/keywords/pending', [KeywordReviewController::class, 'index'])->name('keywords.index');
    Route::patch('/keywords/{keyword}/approve', [KeywordReviewController::class, 'approve'])->name('keywords.approve');
    Route::delete('/keywords/{keyword}/reject', [KeywordReviewController::class, 'reject'])->name('keywords.reject');

});

// Route::resource('keywords', KeywordController::class);
// Route::resource('categories', CategoryController::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

/*

{

}

*/
