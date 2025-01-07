<?php

use App\Http\Controllers\products\ProductController;
use App\Http\Controllers\suppliers\SupplierController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Favourites\FavouriteController;
use App\Http\Controllers\Rates\RateController;


Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('admin/products/create',[ProductController::class,'create'])->name('admin.products.create');
Route::get('customer/products/{id}', [ProductController::class, 'show'])->name('customer.products.show');
Route::get('admin/products/{id}', [ProductController::class, 'show'])->name('admin.products.show');

Route::put('admin/products/{id}',[ProductController::class,'update'])->name('admin.products.update');
Route::get('admin/products/{id}/edit',[ProductController::class,'edit'])->name('admin.products.edit');


Route::post('admin/products/',[ProductController::class,'store'])->name('admin.products.store');

Route::delete('admin/products/{id}',[ProductController::class,'destroy'])->name('admin.products.destroy');


Route::post('/favourites/toggle/{product}', [FavouriteController::class, 'toggle'])->name('customer.favourites.toggle');

Route::get('/customer/products/favourite/i', [ProductController::class, 'getUserFavouriteProducts'])->name('customer.products.favourite');

Route::get('/customer/products/purchased/i', [ProductController::class, 'getUserPurchasedProducts'])->name('customer.products.purchased');


Route::post('/products/{productId}/rate', [RateController::class, 'store'])->name('rates.store');
Route::get('/products/rate/{id}',[RateController::class,'show'])->name('rates.show');
// Route::resource('products',ProductController::class);

Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');

Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');

Route::get('/suppliers/{id}', [SupplierController::class, 'show'])->name('suppliers.show');

Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');

Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');

Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');

Route::get('customer/products/top-rated-products/i', [ProductController::class, 'topRated'])->name('customer.products.topRated');

Route::get('customer/products/top-Favourite-products/i', [ProductController::class, 'topFavouriteProducts'])->name('customer.products.topFavourite');

Route::get('admin/products/product-rates/i',[ProductController::class,'showProductRatings'])->name('admin.products.ratelist');
require __DIR__ . '/auth.php';
