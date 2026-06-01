<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\LinkController as AdminLinkController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Pengguna\DashboardController as PenggunaDashboardController;
use Illuminate\Support\Facades\Route;

// Root -> login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::middleware('guest:admin,pengguna')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Dashboard Pengguna
Route::middleware('auth:pengguna')->group(function () {
    Route::get('/dashboard', [PenggunaDashboardController::class, 'pengguna'])
        ->name('pengguna.dashboard');
    Route::put('/pengguna/password', [PenggunaDashboardController::class, 'updatePenggunaPassword'])
        ->name('pengguna.password.update');
    Route::post('/pengguna/links', [PenggunaDashboardController::class, 'storeUserLink'])
        ->name('pengguna.links.store');
    Route::put('/pengguna/links/{id}', [PenggunaDashboardController::class, 'updateUserLink'])
        ->name('pengguna.links.update');
    Route::delete('/pengguna/links/{id}', [PenggunaDashboardController::class, 'deleteUserLink'])
        ->name('pengguna.links.destroy');
    Route::post('/pengguna/profile', [PenggunaDashboardController::class, 'updateProfile'])
        ->name('pengguna.profile.update');
});

// Dashboard Admin
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'admin'])
        ->name('admin.dashboard');
    Route::get('/admin/services', [AdminServiceController::class, 'adminServices'])
        ->name('admin.services');
    Route::get('/admin/links', [AdminLinkController::class, 'adminLinks'])
        ->name('admin.links');
    Route::post('/admin/links', [AdminLinkController::class, 'storeLink'])
        ->name('admin.links.store');
    Route::put('/admin/links/{id}', [AdminLinkController::class, 'updateLink'])
        ->name('admin.links.update');
    Route::delete('/admin/links/{id}', [AdminLinkController::class, 'deleteLink'])
        ->name('admin.links.destroy');
    Route::post('/admin/links/check', [AdminLinkController::class, 'checkAllLinks'])
        ->name('admin.links.check');
    Route::get('/admin/users', [AdminUserController::class, 'adminUsers'])
        ->name('admin.users');
    Route::post('/admin/users', [AdminUserController::class, 'storeUser'])
        ->name('admin.users.store');
    Route::put('/admin/users/{nik}', [AdminUserController::class, 'updateUser'])
        ->name('admin.users.update');
    Route::delete('/admin/users/{nik}', [AdminUserController::class, 'deleteUser'])
        ->name('admin.users.destroy');
    Route::get('/admin/categories', [AdminCategoryController::class, 'adminCategories'])->name('admin.categories');
    Route::post('/admin/categories', [AdminCategoryController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/admin/categories/{id}', [AdminCategoryController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/admin/categories/{id}', [AdminCategoryController::class, 'deleteCategory'])->name('admin.categories.destroy');
    Route::get('/admin/tags', [AdminTagController::class, 'adminTags'])->name('admin.tags');
    Route::post('/admin/tags', [AdminTagController::class, 'storeTag'])->name('admin.tags.store');
    Route::put('/admin/tags/{id}', [AdminTagController::class, 'updateTag'])->name('admin.tags.update');
    Route::delete('/admin/tags/{id}', [AdminTagController::class, 'deleteTag'])->name('admin.tags.destroy');
    Route::put('/admin/password', [AdminDashboardController::class, 'updateAdminPassword'])->name('admin.password.update');
    Route::post('/admin/profile', [AdminDashboardController::class, 'updateProfile'])->name('admin.profile.update');
});
