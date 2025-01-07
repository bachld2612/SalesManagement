<?php

use App\Http\Controllers\products\ProductController;
use App\Http\Controllers\suppliers\SupplierController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');

Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');

Route::get('/suppliers/{id}', [SupplierController::class, 'show'])->name('suppliers.show');

Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');

Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');

Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');


require __DIR__ . '/auth.php';
