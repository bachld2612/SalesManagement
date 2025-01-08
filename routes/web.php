<?php

use App\Http\Controllers\DashBoard;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::get('/dashboard', function () {
//     return view('dashboard/main');
// });

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
// Route::resource('tasks', TaskController::class);
// Route::resource('users', UserController::class);

require __DIR__ . '/auth.php';
