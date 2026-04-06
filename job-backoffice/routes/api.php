<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KeywordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('/keywords', [KeywordController::class, 'store']);
// Route::post('/categories', [CategoryController::class, 'store']);

