<?php

use App\Http\Controllers\DashBoard;
use App\Http\Controllers\UserController;
use App\Http\Controllers\carts\CartController;
use App\Http\Controllers\orders\OrderController;
use App\Http\Controllers\products\ProductController;
use App\Http\Controllers\suppliers\SupplierController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Favourites\FavouriteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Rates\RateController;



// Route::get('/dashboard', function () {
//     return view('dashboard/main');
// });
Route::get('/', [ProductController::class, 'index'])->name('products.index');


Route::get('/dashboard', [DashBoard::class, 'displayResults'])->name('dashboard.index');
Route::get('/dashboard/customer', [DashBoard::class, 'displayCustomer'])->name('dashboard.customer');
Route::get('/dashboard/employees', [DashBoard::class, 'displayEmployees'])->name('dashboard.employees');
Route::get('/dashboard/customer/add', [DashBoard::class, 'addCustomer'])->name('dashboard.customer.create');
Route::get('/dashboard/employees/add', [DashBoard::class, 'addEmployees'])->name('dashboard.employees.create');

Route::post('/dashboard/customer/add', [DashBoard::class, 'store'])->name('dashboard.customer.store');
Route::post('/dashboard/employees/add', [DashBoard::class, 'storeEmployees'])->name('dashboard.employees.store');

Route::get('/dashboard/customer/edit/{id}', [DashBoard::class, 'editCustomer'])->name('users.edit');
Route::put('/dashboard/customer/edit/{id}', [DashBoard::class, 'update'])->name('users.update');
Route::delete('/dashboard/customer/delete/{id}', [DashBoard::class, 'destroy'])->name('users.delete');

Route::get('/dashboard/products', [ProductController::class, 'index'])->name('dashboard.products.index');
// Route::resource('tasks', TaskController::class);
// Route::resource('users', UserController::class);


// Route::get('admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
Route::get('customer/products/{id}', [ProductController::class, 'show'])->name('customer.products.show');

// Route::get('admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
Route::get('admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
Route::get('admin/products/{id}', [ProductController::class, 'show'])->name('admin.products.show');
Route::put('admin/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
Route::get('admin/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
Route::post('admin/products/', [ProductController::class, 'store'])->name('admin.products.store');
Route::delete('admin/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');


Route::post('/favourites/toggle/{product}', [FavouriteController::class, 'toggle'])->name('customer.favourites.toggle');

Route::get('/customer/products/favourite/i', [ProductController::class, 'getUserFavouriteProducts'])->name('customer.products.favourite');

Route::get('/customer/products/purchased/i', [ProductController::class, 'getUserPurchasedProducts'])->name('customer.products.purchased');


Route::post('/products/{productId}/rate', [RateController::class, 'store'])->name('rates.store');
Route::get('/products/rate/{id}', [RateController::class, 'show'])->name('rates.show');
// Route::resource('products',ProductController::class);

Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');

Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');

Route::get('/suppliers/{id}', [SupplierController::class, 'show'])->name('suppliers.show');

Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');

Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');

Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');

Route::get('customer/products/top-rated-products/i', [ProductController::class, 'topRated'])->name('customer.products.topRated');

Route::get('customer/products/top-Favourite-products/i', [ProductController::class, 'topFavouriteProducts'])->name('customer.products.topFavourite');

Route::get('/customer/carts', [CartController::class, 'index'])->name('customer.carts.index');
Route::post('/customer/carts/handle', [CartController::class, 'handleCart'])->name('customer.carts.handle');
Route::post('/customer/carts/{product}', [CartController::class, 'store'])->name('customer.carts.store');

Route::get('customer/orders', [OrderController::class, 'index'])->name('customer.orders.index');
Route::put('customer/orders/{id}', [OrderController::class, 'destroy'])->name('customer.orders.destroy');

Route::get('customer/orders/{id}', [OrderController::class, 'show'])->name('customer.orders.show');

Route::get('admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
Route::post('admin/orders/{id}', [OrderController::class, 'completeOrder'])->name('admin.orders.complete');
Route::put('admin/orders/{id}/deliver', [OrderController::class, 'deliverOrder'])->name('admin.orders.deliver');
Route::get('admin/products/product-rates/i', [ProductController::class, 'showProductRatings'])->name('admin.products.ratelist');



Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.get');
// web.php
Route::get('/notifications/count', [NotificationController::class, 'count'])->name('notifications.count');


require __DIR__ . '/auth.php';
