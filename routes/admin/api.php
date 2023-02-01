<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:admin_api',
])->group(function () {

    Route::group([], function () {
        Route::post('login', [\App\Http\Controllers\Api\Admin\AdminController::class, 'login'])->name('api.admin.login')->withoutMiddleware(['auth:admin_api']);
        Route::post('theme', [\App\Http\Controllers\Api\Admin\AdminController::class, 'updateTheme'])->name('api.admin.theme');
    });

    Route::prefix('user')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\Admin\UserController::class, 'getAll'])->name('api.admin.user.getAll');
        Route::get('getById', [\App\Http\Controllers\Api\Admin\UserController::class, 'getById'])->name('api.admin.user.getById');
        Route::put('update', [\App\Http\Controllers\Api\Admin\UserController::class, 'update'])->name('api.admin.user.update');
    });

    Route::prefix('subscription')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\Admin\SubscriptionController::class, 'getAll'])->name('api.admin.subscription.getAll');
        Route::post('create', [\App\Http\Controllers\Api\Admin\SubscriptionController::class, 'create'])->name('api.admin.subscription.create');
    });

});
