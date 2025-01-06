<?php

use App\Http\Controllers\products\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ProductController::class, 'index'])->name('products.index');

Route::get('customer/products/{id}', [ProductController::class, 'show'])->name('customer.products.show');
Route::get('admin/products/{id}', [ProductController::class, 'show'])->name('admin.products.show');

Route::put('admin/products/{id}',[ProductController::class,'update'])->name('admin.products.update');
Route::get('admin/products/{id}/edit',[ProductController::class,'edit'])->name('admin.products.edit');

Route::get('admin/products/create',[ProductController::class,'create'])->name('admin.products.create');
Route::post('admin/products',[ProductController::class,'store'])->name('admin.products.store');

Route::delete('admin/products/{id}',[ProductController::class,'destroy'])->name('admin.products.destroy');

// Route::resource('products',ProductController::class);
require __DIR__.'/auth.php';
