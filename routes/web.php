<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('home');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'can:is-admin'])->group(function () {
    Route::resource('/user', UserController::class)->names([
        'index'   => 'user.index',
        'create'  => 'user.new',
        'store'   => 'user.save',
        'show'    => 'user.view',
        'edit'    => 'user.modify',
        'update'  => 'user.update',
        'destroy' => 'user.delete',
    ]);

    Route::resource('/category', CategoryController::class)->names([
        'index'   => 'category.index',
        'create'  => 'category.new',
        'store'   => 'category.save',
        'show'    => 'category.view',
        'edit'    => 'category.modify',
        'update'  => 'category.update',
        'destroy' => 'category.delete',
    ]);
});


require __DIR__.'/auth.php';
