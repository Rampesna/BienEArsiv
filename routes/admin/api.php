<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([
    'auth:admin_api',
])->group(function () {

    Route::group([], function () {
        Route::post('login', [\App\Http\Controllers\Api\Admin\AdminController::class, 'login'])->name('api.admin.login')->withoutMiddleware(['auth:admin_api']);
    });


});
