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
        Route::get('index', [\App\Http\Controllers\Api\User\TransactionTypeController::class, 'index'])->name('api.user.transactionType.index');
    });

    Route::prefix('unit')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\UnitController::class, 'getAll'])->name('api.user.unit.getAll');
    });

    Route::prefix('country')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\CountryController::class, 'getAll'])->name('api.user.country.getAll');
    });

    Route::prefix('province')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\ProvinceController::class, 'getAll'])->name('api.user.province.getAll');
        Route::get('getByCountryId', [\App\Http\Controllers\Api\User\ProvinceController::class, 'getByCountryId'])->name('api.user.province.getByCountryId');
    });

    Route::prefix('district')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\DistrictController::class, 'getAll'])->name('api.user.district.getAll');
        Route::get('getByProvinceId', [\App\Http\Controllers\Api\User\DistrictController::class, 'getByProvinceId'])->name('api.user.district.getByProvinceId');
    });

    Route::prefix('company')->group(function () {
        Route::get('all', [\App\Http\Controllers\Api\User\CompanyController::class, 'all'])->name('api.user.company.all');
        Route::get('index', [\App\Http\Controllers\Api\User\CompanyController::class, 'index'])->name('api.user.company.index');
        Route::get('getById', [\App\Http\Controllers\Api\User\CompanyController::class, 'getById'])->name('api.user.company.getById');
        Route::post('create', [\App\Http\Controllers\Api\User\CompanyController::class, 'create'])->name('api.user.company.create');
        Route::put('update', [\App\Http\Controllers\Api\User\CompanyController::class, 'update'])->name('api.user.company.update');
        Route::delete('delete', [\App\Http\Controllers\Api\User\CompanyController::class, 'delete'])->name('api.user.company.delete');
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
        Route::get('all', [\App\Http\Controllers\Api\User\TransactionController::class, 'all'])->name('api.user.transaction.all');
        Route::get('count', [\App\Http\Controllers\Api\User\TransactionController::class, 'count'])->name('api.user.transaction.count');
        Route::get('index', [\App\Http\Controllers\Api\User\TransactionController::class, 'index'])->name('api.user.transaction.index');
        Route::post('create', [\App\Http\Controllers\Api\User\TransactionController::class, 'create'])->name('api.user.transaction.create');
    });

    Route::prefix('invoice')->group(function () {
        Route::get('index', [\App\Http\Controllers\Api\User\InvoiceController::class, 'index'])->name('api.user.invoice.index');
        Route::post('create', [\App\Http\Controllers\Api\User\InvoiceController::class, 'create'])->name('api.user.invoice.create');
    });

    Route::prefix('invoiceProduct')->group(function () {
        Route::post('create', [\App\Http\Controllers\Api\User\InvoiceProductController::class, 'create'])->name('api.user.invoiceProduct.create');
    });
});
