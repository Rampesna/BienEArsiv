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

Route::prefix('customer')->group(function () {
    Route::post('create', [\App\Http\Controllers\Api\User\CustomerController::class, 'create'])->name('api.user.customer.create');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::group([
        'withoutMiddleware' => [
            'auth:sanctum'
        ],
    ], function () {
        Route::post('login', [\App\Http\Controllers\Api\User\UserController::class, 'login'])->name('api.user.login')->withoutMiddleware('auth:sanctum');
        Route::post('create', [\App\Http\Controllers\Api\User\UserController::class, 'create'])->name('api.user.create')->withoutMiddleware('auth:sanctum');
    });

    Route::get('show', [\App\Http\Controllers\Api\User\UserController::class, 'show'])->name('api.user.show');
    Route::post('updateTheme', [\App\Http\Controllers\Api\User\UserController::class, 'updateTheme'])->name('api.user.updateTheme');

    Route::prefix('safeboxType')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\SafeboxTypeController::class, 'getAll'])->name('api.user.safeboxType.getAll');
    });

    Route::prefix('transactionType')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\TransactionTypeController::class, 'getAll'])->name('api.user.transactionType.getAll');
    });

    Route::prefix('unit')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\UnitController::class, 'getAll'])->name('api.user.unit.getAll');
    });

    Route::prefix('country')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\CountryController::class, 'getAll'])->name('api.user.country.getAll');
    });

    Route::prefix('province')->group(function () {
        Route::get('getByCountryId', [\App\Http\Controllers\Api\User\ProvinceController::class, 'getByCountryId'])->name('api.user.province.getByCountryId');
    });

    Route::prefix('district')->group(function () {
        Route::get('getByProvinceId', [\App\Http\Controllers\Api\User\DistrictController::class, 'getByProvinceId'])->name('api.user.district.getByProvinceId');
    });

    Route::prefix('company')->group(function () {
        Route::get('index', [\App\Http\Controllers\Api\User\CompanyController::class, 'index'])->name('api.user.company.index');
        Route::get('getById', [\App\Http\Controllers\Api\User\CompanyController::class, 'getById'])->name('api.user.company.getById');
        Route::post('create', [\App\Http\Controllers\Api\User\CompanyController::class, 'create'])->name('api.user.company.create');
    });

    Route::prefix('safebox')->group(function () {
        Route::get('all', [\App\Http\Controllers\Api\User\SafeboxController::class, 'all'])->name('api.user.safebox.all');
        Route::get('index', [\App\Http\Controllers\Api\User\SafeboxController::class, 'index'])->name('api.user.safebox.index');
        Route::get('getById', [\App\Http\Controllers\Api\User\SafeboxController::class, 'getById'])->name('api.user.safebox.getById');
        Route::post('create', [\App\Http\Controllers\Api\User\SafeboxController::class, 'create'])->name('api.user.safebox.create');
    });

    Route::prefix('product')->group(function () {
        Route::get('all', [\App\Http\Controllers\Api\User\ProductController::class, 'all'])->name('api.user.product.all');
        Route::get('index', [\App\Http\Controllers\Api\User\ProductController::class, 'index'])->name('api.user.product.index');
        Route::get('getById', [\App\Http\Controllers\Api\User\ProductController::class, 'getById'])->name('api.user.product.getById');
        Route::post('create', [\App\Http\Controllers\Api\User\ProductController::class, 'create'])->name('api.user.product.create');
    });

    Route::prefix('transaction')->group(function () {
        Route::get('index', [\App\Http\Controllers\Api\User\TransactionController::class, 'index'])->name('api.user.transaction.index');
        Route::post('createCredit', [\App\Http\Controllers\Api\User\TransactionController::class, 'createCredit'])->name('api.user.transaction.createCredit');
        Route::post('createDebit', [\App\Http\Controllers\Api\User\TransactionController::class, 'createDebit'])->name('api.user.transaction.createDebit');
        Route::post('createCollection', [\App\Http\Controllers\Api\User\TransactionController::class, 'createCollection'])->name('api.user.transaction.createCollection');
        Route::post('createPayment', [\App\Http\Controllers\Api\User\TransactionController::class, 'createPayment'])->name('api.user.transaction.createPayment');
        Route::post('createEarn', [\App\Http\Controllers\Api\User\TransactionController::class, 'createEarn'])->name('api.user.transaction.createEarn');
        Route::post('createExpense', [\App\Http\Controllers\Api\User\TransactionController::class, 'createExpense'])->name('api.user.transaction.createExpense');
    });
});
