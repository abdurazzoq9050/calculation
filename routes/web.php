<?php

use App\Http\Controllers\IngredientsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeHistoriesController;
use App\Http\Controllers\UserController;
use App\Models\RecipeHistories;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login');
})->name('login');


Route::post('/login', [UserController::class, 'loginv2'])->name('loginPost')->middleware('throttle:3,1');


Route::middleware('auth')->group(function () {
    
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');
    
    // Route::get('/employee', [UserController::class, 'employees'])->name('employees.index');
    
    Route::prefix('employees')->middleware('auth')->group(function () {
        Route::get('/', [UserController::class, 'employees'])->name('employees.index');
        Route::get('/create', [UserController::class, 'create'])->name('employees.create');
        Route::post('/', [UserController::class, 'storev2'])->name('employees.store');
        Route::patch('/{id}', [UserController::class, 'updatev2'])->name('employees.update');
        Route::delete('/{id}', [UserController::class, 'destroyv2'])->name('employees.destroy');
    });

    Route::get('/employees-edit/{id}', [UserController::class, 'edit'])->name('employees.edit');

    Route::prefix('products')->middleware('auth')->group(function () {
        Route::get('/', [ProductController::class, 'products'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/', [ProductController::class, 'storev2'])->name('products.store');
        Route::patch('/edit', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::post('/recipe', [RecipeController::class, 'storev2'])->name('recipe.store');
        Route::patch('/{id}', [RecipeController::class, 'updatev2'])->name('recipe.update');
        Route::delete('/delete/{id}', [RecipeController::class, 'destroy'])->name('recipe.destroy');
    });

    Route::get('/products-edit/{id}', [ProductController::class, 'edit'])->name('products.show');



    Route::prefix('ingredients')->middleware('auth')->group(function () {
        Route::get('/', [IngredientsController::class, 'ingredients'])->name('ingredients.index');
        Route::post('/', [IngredientsController::class, 'storev2'])->name('ingredients.store');
        Route::patch('/edit', [IngredientsController::class, 'updatev2'])->name('ingredients.update');
        Route::delete('/{id}', [IngredientsController::class, 'destroyv2'])->name('ingredients.destroy');
    });


    Route::get('/recipeHistory', [ RecipeHistoriesController::class, 'index' ])->name('recipeHistory.index');


})->middleware('role:Разраб,Администратор');