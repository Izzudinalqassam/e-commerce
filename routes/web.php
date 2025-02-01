<?php

use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Auth::routes();
Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});
Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    // Brands
    Route::get('admin/brand', [AdminController::class, 'brands'])->name('admin.brand');
    Route::get('admin/brand/add', [AdminController::class, 'add_brand'])->name('admin.brand.add');
    Route::post('admin/brand/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
    Route::get('admin/brand/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');
    Route::put('admin/brand/update/{id}', [AdminController::class, 'brand_update'])->name('admin.brand.update');
    Route::delete('admin/brand/delete/{id}', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');
    // Categories
    Route::get('admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('admin/category/add', [AdminController::class, 'categories_add'])->name('admin.category.add');
    Route::post('admin/category/store', [AdminController::class, 'categories_store'])->name('admin.category.store');
    Route::get('admin/category/edit/{id}', [AdminController::class, 'categories_edit'])->name('admin.category.edit');
    Route::put('admin/category/update/{id}', [AdminController::class, 'categories_update'])->name('admin.category.update');
    Route::delete('admin/category/delete/{id}', [AdminController::class, 'categories_delete'])->name('admin.category.delete');
    // Products
    Route::get('admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('admin/product/add', [AdminController::class, 'products_add'])->name('admin.product.add');
    Route::post('admin/product/store', [AdminController::class, 'product_store'])->name('admin.product.store');
    Route::get('admin/product/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.product.edit');
    Route::put('admin/product/update/{id}', [AdminController::class, 'update_product'])->name('admin.product.update');
    Route::delete('admin/product/delete/{id}', [AdminController::class, 'delete_product'])->name('admin.product.delete');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
