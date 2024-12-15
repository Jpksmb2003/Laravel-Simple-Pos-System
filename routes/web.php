<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoriesController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('Showlogin');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/reset', [AuthController::class, 'showResetForm'])->name('reset');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/users', [AuthController::class, 'index'])->name('user.index'); // Show list of users
    Route::get('/users/{id}/edit', [AuthController::class, 'ShowEditForm'])->name('user.edit'); // Show edit form
    Route::put('/users/{id}', [AuthController::class, 'update'])->name('user.update'); // Update user (Corrected)
    Route::delete('/users/{id}', [AuthController::class, 'destroy'])->name('user.delete'); // Delete user
});



Route::middleware(['auth', RoleMiddleware::class . ':saler'])->group(function () {
    Route::get('/sales', [SalesController::class, 'index'])->name('sale.index');
    Route::get('/sales/sale_product', [SalesController::class, 'create'])->name('sale.sale_product');
    Route::post('/sales', [SalesController::class, 'store'])->name('sale.store');
    Route::get('/sales/{id}/edit', [SalesController::class, 'edit'])->name('sale.edit');
    Route::put('/sales/{id}', [SalesController::class, 'update'])->name('sale.update');
    Route::delete('/sales/{id}', [SalesController::class, 'destroy'])->name('sale.delete');
});

Route::middleware(['auth', RoleMiddleware::class . ':manager'])->group(function () {
    Route::get('/inventory', [InventoriesController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoriesController::class, 'create']);
    Route::post('/inventory', [InventoriesController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{id}/edit', [InventoriesController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{id}', [InventoriesController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{id}', [InventoriesController::class, 'destroy'])->name('inventory.delete');
});
