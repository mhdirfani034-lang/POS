<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');
Route::resource('produk', ProductController::class)->except('show')->parameters(['produk' => 'product']);
Route::resource('pelanggan', CustomerController::class)->except('show')->parameters(['pelanggan' => 'customer']);
Route::get('/kasir', [PosController::class, 'create'])->name('pos.create');
Route::post('/kasir', [PosController::class, 'store'])->name('pos.store');
Route::get('/transaksi', [OrderController::class, 'index'])->name('orders.index');
Route::get('/transaksi/{order}', [OrderController::class, 'show'])->name('orders.show');
