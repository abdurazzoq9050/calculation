<?php

use App\Http\Controllers\IngredientsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);
// Route::post('/register', [UserController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', [UserController::class, 'show']);
    Route::patch('user/{user}', [UserController::class, 'update'])->middleware('role:Разраб');
    Route::delete('user/{user}', [UserController::class, 'destroy'])->middleware('role:Разраб');
    
    Route::get('ingredients', [IngredientsController::class, 'index']);
    Route::get('ingredients/{ingredient}', [IngredientsController::class, 'show']);
    Route::patch('ingredients/{ingredient}', [IngredientsController::class, 'update'])->middleware('role:Разраб,Администратор');
    Route::delete('ingredients/{ingredient}', [IngredientsController::class, 'destroy'])->middleware('role:Разраб,Администратор');
    
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::patch('products/{product}', [ProductController::class, 'update']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::delete('/products/{product}',[ProductController::class, 'destroy']);

    
});