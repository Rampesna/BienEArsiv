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

    Route::get('index', [\App\Http\Controllers\Api\User\UserController::class, 'index'])->name('api.user.index');
    Route::get('getById', [\App\Http\Controllers\Api\User\UserController::class, 'getById'])->name('api.user.getById');
    Route::get('count', [\App\Http\Controllers\Api\User\UserController::class, 'count'])->name('api.user.count');
    Route::put('update', [\App\Http\Controllers\Api\User\UserController::class, 'update'])->name('api.user.update');
    Route::delete('delete', [\App\Http\Controllers\Api\User\UserController::class, 'delete'])->name('api.user.delete');

    Route::prefix('customer')->group(function () {
        Route::get('getById', [\App\Http\Controllers\Api\User\CustomerController::class, 'getById'])->name('api.user.customer.getById');
        Route::put('update', [\App\Http\Controllers\Api\User\CustomerController::class, 'update'])->name('api.user.customer.update');
        Route::post('updateStamp', [\App\Http\Controllers\Api\User\CustomerController::class, 'updateStamp'])->name('api.user.customer.updateStamp');
        Route::post('updateLogo', [\App\Http\Controllers\Api\User\CustomerController::class, 'updateLogo'])->name('api.user.customer.updateLogo');
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

    Route::prefix('transactionCategory')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\TransactionCategoryController::class, 'getAll'])->name('api.user.transactionCategory.getAll');
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
        Route::get('getTotalBalance', [\App\Http\Controllers\Api\User\SafeboxController::class, 'getTotalBalance'])->name('api.user.safebox.getTotalBalance');
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
        Route::get('count', [\App\Http\Controllers\Api\User\InvoiceController::class, 'count'])->name('api.user.invoice.count');
        Route::get('getById', [\App\Http\Controllers\Api\User\InvoiceController::class, 'getById'])->name('api.user.invoice.getById');
        Route::post('create', [\App\Http\Controllers\Api\User\InvoiceController::class, 'create'])->name('api.user.invoice.create');
        Route::put('update', [\App\Http\Controllers\Api\User\InvoiceController::class, 'update'])->name('api.user.invoice.update');
    });

    Route::prefix('invoiceProduct')->group(function () {
        Route::get('getByInvoiceId', [\App\Http\Controllers\Api\User\InvoiceProductController::class, 'getByInvoiceId'])->name('api.user.invoiceProduct.getByInvoiceId');
        Route::post('create', [\App\Http\Controllers\Api\User\InvoiceProductController::class, 'create'])->name('api.user.invoiceProduct.create');
        Route::put('update', [\App\Http\Controllers\Api\User\InvoiceProductController::class, 'update'])->name('api.user.invoiceProduct.update');
    });

    Route::prefix('customerUnit')->group(function () {
        Route::get('all', [\App\Http\Controllers\Api\User\CustomerUnitController::class, 'all'])->name('api.user.customerUnit.all');
        Route::get('index', [\App\Http\Controllers\Api\User\CustomerUnitController::class, 'index'])->name('api.user.customerUnit.index');
        Route::get('getById', [\App\Http\Controllers\Api\User\CustomerUnitController::class, 'getById'])->name('api.user.customerUnit.getById');
        Route::post('create', [\App\Http\Controllers\Api\User\CustomerUnitController::class, 'create'])->name('api.user.customerUnit.create');
        Route::put('update', [\App\Http\Controllers\Api\User\CustomerUnitController::class, 'update'])->name('api.user.customerUnit.update');
        Route::delete('delete', [\App\Http\Controllers\Api\User\CustomerUnitController::class, 'delete'])->name('api.user.customerUnit.delete');
    });

    Route::prefix('customerTransactionCategory')->group(function () {
        Route::get('all', [\App\Http\Controllers\Api\User\CustomerTransactionCategoryController::class, 'all'])->name('api.user.customerTransactionCategory.all');
        Route::get('index', [\App\Http\Controllers\Api\User\CustomerTransactionCategoryController::class, 'index'])->name('api.user.customerTransactionCategory.index');
        Route::get('getById', [\App\Http\Controllers\Api\User\CustomerTransactionCategoryController::class, 'getById'])->name('api.user.customerTransactionCategory.getById');
        Route::post('create', [\App\Http\Controllers\Api\User\CustomerTransactionCategoryController::class, 'create'])->name('api.user.customerTransactionCategory.create');
        Route::put('update', [\App\Http\Controllers\Api\User\CustomerTransactionCategoryController::class, 'update'])->name('api.user.customerTransactionCategory.update');
        Route::delete('delete', [\App\Http\Controllers\Api\User\CustomerTransactionCategoryController::class, 'delete'])->name('api.user.customerTransactionCategory.delete');
    });
});
