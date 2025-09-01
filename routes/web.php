<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DispositionController;
use App\Http\Controllers\IncomingLetterController;
use App\Http\Controllers\OutgoingLetterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('home');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/dashboard/dispositions/{disposition}/read', [DashboardController::class, 'markDispositionAsRead'])->name('dashboard.markAsRead');

    Route::get('/document/{letter}/file', [DashboardController::class, 'streamFile'])->name('document.file');

    Route::resource('/incoming-letter', IncomingLetterController::class)->names([
        'index'   => 'incomingLetter.index',
        'create'  => 'incomingLetter.new',
        'store'   => 'incomingLetter.save',
        'show'    => 'incomingLetter.view',
        'edit'    => 'incomingLetter.modify',
        'update'  => 'incomingLetter.update',
        'destroy' => 'incomingLetter.delete',
    ]);

    Route::resource('/outgoing-letter', OutgoingLetterController::class)->names([
        'index'   => 'outgoingLetter.index',
        'create'  => 'outgoingLetter.new',
        'store'   => 'outgoingLetter.save',
        'show'    => 'outgoingLetter.view',
        'edit'    => 'outgoingLetter.modify',
        'update'  => 'outgoingLetter.update',
        'destroy' => 'outgoingLetter.delete',
    ]);

    Route::resource('/disposition-incoming-letter', DispositionController::class)->names([
        'index'   => 'dispositionIncomingLetter.index',
        'show'    => 'dispositionIncomingLetter.view',
        'edit'    => 'dispositionIncomingLetter.modify',
        'update'  => 'dispositionIncomingLetter.update',
        'destroy' => 'dispositionIncomingLetter.delete',
    ])->except('create', 'store');
    Route::get('/disposition-incoming-letter/create/{letter}', [DispositionController::class, 'create'])->name('dispositionIncomingLetter.new');
    Route::post('/disposition-incoming-letter/store/{letter}', [DispositionController::class, 'store'])->name('dispositionIncomingLetter.save');
    Route::get('/disposition-incoming-letter/{letter}/print', [DispositionController::class, 'generateDispositionSheetPdf'])->name('dispositionIncomingLetter.print');
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
