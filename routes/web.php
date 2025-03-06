<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;


Route::get('/order', [CustomerController::class, 'orderWithoutSlug'])->name('customer.order.default'); //for development
Route::get('/order/{slug}', [CustomerController::class, 'order'])->name('customer.order');
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::get('/admin/menu', [AdminController::class, 'menus'])->name('admin.menu');
Route::get('/admin/table', [AdminController::class, 'tables'])->name('admin.table');
Route::get('/admin/category', [AdminController::class, 'categories'])->name('admin.category');
Route::get('/admin/order', [AdminController::class, 'orders'])->name('admin.order');
