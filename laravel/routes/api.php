<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(CategoryController::class)->prefix('categories')->group(function()
{
    Route::get('/', 'getCategories');
    Route::post('/', 'createCategory');
    Route::get('/{categoryId}', 'getCategory');
    Route::patch('/{categoryId}', 'updateCategory');
    Route::delete('/{categoryId}', 'deleteCategory');
});

Route::controller(ProductController::class)->prefix('products')->group(function() {
    Route::get('/', 'getProducts'); // To get all products
    Route::post('/', 'createProduct'); // To create a new product
    Route::get('/{productId}', 'getProduct'); // To get a single product by ID
    Route::patch('/{productId}', 'updateProduct'); // To update a product by ID
    Route::delete('/{productId}', 'deleteProduct'); // To delete a product by ID
});
