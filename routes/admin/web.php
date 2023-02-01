<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade as PDF;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route(auth()->guard('admin_web')->check() ? 'web.admin.dashboard.index' : 'web.admin.authentication.login');
});

Route::prefix('authentication')->group(function () {
    Route::get('login', [\App\Http\Controllers\Web\Admin\AuthenticationController::class, 'login'])->name('web.admin.authentication.login');
    Route::get('logout', [\App\Http\Controllers\Web\Admin\AuthenticationController::class, 'logout'])->name('web.admin.authentication.logout');
    Route::get('oAuth', [\App\Http\Controllers\Web\Admin\AuthenticationController::class, 'oAuth'])->name('web.admin.authentication.oAuth');
});

Route::middleware([
    'auth:admin_web'
])->group(function () {

    Route::prefix('dashboard')->group(function () {
        Route::get('index', [\App\Http\Controllers\Web\Admin\DashboardController::class, 'index'])->name('web.admin.dashboard.index');
    });

    Route::prefix('user')->group(function () {
        Route::get('index', [\App\Http\Controllers\Web\Admin\UserController::class, 'index'])->name('web.admin.user.index');
    });


});
