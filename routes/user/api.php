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

Route::middleware([
    'auth:sanctum',
    'SubscriptionApi',
    'WizardApi',
])->group(function () {

    Route::prefix('wizard')->withoutMiddleware([
        'WizardApi',
    ])->group(function () {
        Route::post('complete', [\App\Http\Controllers\Api\User\WizardController::class, 'complete'])->name('api.user.wizard.complete');
    });

    Route::group([], function () {
        Route::post('login', [\App\Http\Controllers\Api\User\UserController::class, 'login'])->name('api.user.login')->withoutMiddleware(['auth:sanctum', 'SubscriptionApi']);
        Route::post('create', [\App\Http\Controllers\Api\User\UserController::class, 'create'])->name('api.user.create')->withoutMiddleware(['auth:sanctum', 'SubscriptionApi']);
        Route::post('sendPasswordResetEmail', [\App\Http\Controllers\Api\User\UserController::class, 'sendPasswordResetEmail'])->name('api.user.sendPasswordResetEmail')->withoutMiddleware(['auth:sanctum', 'SubscriptionApi']);
        Route::post('resetPassword', [\App\Http\Controllers\Api\User\UserController::class, 'resetPassword'])->name('api.user.resetPassword')->withoutMiddleware(['auth:sanctum', 'SubscriptionApi']);
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

    Route::prefix('currency')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\CurrencyController::class, 'getAll'])->name('api.user.currency.getAll');
    });

    Route::prefix('vatDiscount')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\VatDiscountController::class, 'getAll'])->name('api.user.vatDiscount.getAll');
    });

    Route::prefix('subscription')->withoutMiddleware([
        'auth:sanctum',
        'SubscriptionApi'
    ])->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\SubscriptionController::class, 'getAll'])->name('api.user.subscription.getAll');
    });

    Route::prefix('transactionCategory')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\TransactionCategoryController::class, 'getAll'])->name('api.user.transactionCategory.getAll');
    });

    Route::prefix('country')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\CountryController::class, 'getAll'])->name('api.user.country.getAll');
    });

    Route::prefix('taxpayerType')->group(function () {
        Route::get('getAll', [\App\Http\Controllers\Api\User\TaxpayerTypeController::class, 'getAll'])->name('api.user.taxpayerType.getAll');
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
        Route::get('getByTaxNumber', [\App\Http\Controllers\Api\User\CompanyController::class, 'getByTaxNumber'])->name('api.user.company.getByTaxNumber');
        Route::post('create', [\App\Http\Controllers\Api\User\CompanyController::class, 'create'])->name('api.user.company.create');
        Route::put('update', [\App\Http\Controllers\Api\User\CompanyController::class, 'update'])->name('api.user.company.update');
        Route::delete('delete', [\App\Http\Controllers\Api\User\CompanyController::class, 'delete'])->name('api.user.company.delete');

        Route::prefix('report')->group(function () {
            Route::get('balanceStatus', [\App\Http\Controllers\Api\User\CompanyReportController::class, 'balanceStatus'])->name('api.user.company.report.balanceStatus');
            Route::post('extract', [\App\Http\Controllers\Api\User\CompanyReportController::class, 'extract'])->name('api.user.company.report.extract');
            Route::post('transaction', [\App\Http\Controllers\Api\User\CompanyReportController::class, 'transaction'])->name('api.user.company.report.transaction');
        });
    });

    Route::prefix('safebox')->group(function () {
        Route::get('all', [\App\Http\Controllers\Api\User\SafeboxController::class, 'all'])->name('api.user.safebox.all');
        Route::get('index', [\App\Http\Controllers\Api\User\SafeboxController::class, 'index'])->name('api.user.safebox.index');
        Route::get('getById', [\App\Http\Controllers\Api\User\SafeboxController::class, 'getById'])->name('api.user.safebox.getById');
        Route::get('getTotalBalance', [\App\Http\Controllers\Api\User\SafeboxController::class, 'getTotalBalance'])->name('api.user.safebox.getTotalBalance');
        Route::post('create', [\App\Http\Controllers\Api\User\SafeboxController::class, 'create'])->name('api.user.safebox.create');
        Route::put('update', [\App\Http\Controllers\Api\User\SafeboxController::class, 'update'])->name('api.user.safebox.update');
        Route::delete('delete', [\App\Http\Controllers\Api\User\SafeboxController::class, 'delete'])->name('api.user.safebox.delete');
    });

    Route::prefix('product')->group(function () {
        Route::get('all', [\App\Http\Controllers\Api\User\ProductController::class, 'all'])->name('api.user.product.all');
        Route::get('index', [\App\Http\Controllers\Api\User\ProductController::class, 'index'])->name('api.user.product.index');
        Route::get('getById', [\App\Http\Controllers\Api\User\ProductController::class, 'getById'])->name('api.user.product.getById');
        Route::post('create', [\App\Http\Controllers\Api\User\ProductController::class, 'create'])->name('api.user.product.create');
        Route::put('update', [\App\Http\Controllers\Api\User\ProductController::class, 'update'])->name('api.user.product.update');
        Route::delete('delete', [\App\Http\Controllers\Api\User\ProductController::class, 'delete'])->name('api.user.product.delete');

        Route::prefix('report')->group(function () {
            Route::get('all', [\App\Http\Controllers\Api\User\ProductReportController::class, 'all'])->name('api.user.product.report.all');
        });
    });

    Route::prefix('transaction')->group(function () {
        Route::get('all', [\App\Http\Controllers\Api\User\TransactionController::class, 'all'])->name('api.user.transaction.all');
        Route::get('count', [\App\Http\Controllers\Api\User\TransactionController::class, 'count'])->name('api.user.transaction.count');
        Route::get('index', [\App\Http\Controllers\Api\User\TransactionController::class, 'index'])->name('api.user.transaction.index');
        Route::post('create', [\App\Http\Controllers\Api\User\TransactionController::class, 'create'])->name('api.user.transaction.create');

        Route::prefix('report')->group(function () {
            Route::post('get', [\App\Http\Controllers\Api\User\TransactionReportController::class, 'get'])->name('api.user.transaction.report.get');
        });
    });

    Route::prefix('invoice')->group(function () {
        Route::get('index', [\App\Http\Controllers\Api\User\InvoiceController::class, 'index'])->name('api.user.invoice.index');
        Route::get('count', [\App\Http\Controllers\Api\User\InvoiceController::class, 'count'])->name('api.user.invoice.count');
        Route::get('getById', [\App\Http\Controllers\Api\User\InvoiceController::class, 'getById'])->name('api.user.invoice.getById');
        Route::post('create', [\App\Http\Controllers\Api\User\InvoiceController::class, 'create'])->name('api.user.invoice.create');
        Route::put('update', [\App\Http\Controllers\Api\User\InvoiceController::class, 'update'])->name('api.user.invoice.update');
        Route::delete('delete', [\App\Http\Controllers\Api\User\InvoiceController::class, 'delete'])->name('api.user.invoice.delete');
        Route::post('sendToGib', [\App\Http\Controllers\Api\User\InvoiceController::class, 'sendToGib'])->name('api.user.invoice.sendToGib');
    });

    Route::prefix('eInvoice')->group(function () {
        Route::post('login', [\App\Http\Controllers\Api\User\EInvoiceController::class, 'login'])->name('api.user.eInvoice.login');
        Route::post('logout', [\App\Http\Controllers\Api\User\EInvoiceController::class, 'logout'])->name('api.user.eInvoice.logout');
        Route::get('outbox', [\App\Http\Controllers\Api\User\EInvoiceController::class, 'outbox'])->name('api.user.eInvoice.outbox');
        Route::get('inbox', [\App\Http\Controllers\Api\User\EInvoiceController::class, 'inbox'])->name('api.user.eInvoice.inbox');
        Route::post('sendSmsVerification', [\App\Http\Controllers\Api\User\EInvoiceController::class, 'sendSmsVerification'])->name('api.user.eInvoice.sendSmsVerification');
        Route::post('verifySmsCode', [\App\Http\Controllers\Api\User\EInvoiceController::class, 'verifySmsCode'])->name('api.user.eInvoice.verifySmsCode');
        Route::post('cancelEInvoice', [\App\Http\Controllers\Api\User\EInvoiceController::class, 'cancelEInvoice'])->name('api.user.eInvoice.cancelEInvoice');
        Route::get('getInvoiceHTML', [\App\Http\Controllers\Api\User\EInvoiceController::class, 'getInvoiceHTML'])->name('api.user.eInvoice.getInvoiceHTML');
        Route::get('getInvoicePDF', [\App\Http\Controllers\Api\User\EInvoiceController::class, 'getInvoicePDF'])->name('api.user.eInvoice.getInvoicePDF');

        Route::prefix('report')->group(function () {
            Route::post('outbox', [\App\Http\Controllers\Api\User\EInvoiceReportController::class, 'outbox'])->name('api.user.eInvoice.report.outbox');
            Route::post('inbox', [\App\Http\Controllers\Api\User\EInvoiceReportController::class, 'inbox'])->name('api.user.eInvoice.report.inbox');
        });
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

    Route::prefix('subscriptionPayment')->group(function () {
        Route::post('create', [\App\Http\Controllers\Api\User\SubscriptionPaymentController::class, 'create'])->name('api.user.subscriptionPayment.create');
        Route::post('success', [\App\Http\Controllers\Api\User\SubscriptionPaymentController::class, 'successUrl'])->name('api.user.subscriptionPayment.successUrl')->withoutMiddleware(['auth:sanctum', 'SubscriptionApi']);
        Route::post('failure', [\App\Http\Controllers\Api\User\SubscriptionPaymentController::class, 'failureUrl'])->name('api.user.subscriptionPayment.failureUrl')->withoutMiddleware(['auth:sanctum', 'SubscriptionApi']);
    });

    Route::prefix('customerSubscription')->withoutMiddleware([
        'SubscriptionApi'
    ])->group(function () {
        Route::get('check', [\App\Http\Controllers\Api\User\CustomerSubscriptionController::class, 'check'])->name('api.user.customerSubscription.check');
    });
});
