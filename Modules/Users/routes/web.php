<?php
use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['permission:system.users.view'])->group(function () {
        Route::get('users', [UsersController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UsersController::class, 'show'])->name('users.show');
    });

    Route::middleware(['permission:system.users.create'])->group(function () {
        Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
        Route::post('users', [UsersController::class, 'store'])->name('users.store');
    });

    Route::middleware(['permission:system.users.edit'])->group(function () {
        Route::get('users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UsersController::class, 'update'])->name('users.update');
        Route::patch('users/{user}', [UsersController::class, 'update']);
    });

    Route::middleware(['permission:system.users.delete'])->group(function () {
        Route::delete('users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
    });
});
