<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/order', function () {
    return view('order');
});
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::get('/admin/menu', [AdminController::class, 'menus'])->name('admin.menu');
