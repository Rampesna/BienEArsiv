<?php

use Illuminate\Support\Facades\Route;

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

Route::get('login', function () {
    return redirect()->route('web.user.authentication.login');
})->name('login');

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'web.user.dashboard.index' : 'web.user.authentication.login');
});

Route::prefix('authentication')->group(function () {
    Route::get('login', [\App\Http\Controllers\Web\User\AuthenticationController::class, 'login'])->name('web.user.authentication.login');
    Route::get('logout', [\App\Http\Controllers\Web\User\AuthenticationController::class, 'logout'])->name('web.user.authentication.logout');
    Route::get('oAuth', [\App\Http\Controllers\Web\User\AuthenticationController::class, 'oAuth'])->name('web.user.authentication.oAuth');
    Route::get('register', [\App\Http\Controllers\Web\User\AuthenticationController::class, 'register'])->name('web.user.authentication.register');
    Route::get('forgotPassword', [\App\Http\Controllers\Web\User\AuthenticationController::class, 'forgotPassword'])->name('web.user.authentication.forgotPassword');
});

Route::middleware([
    'auth',
    'SubscriptionWeb',
])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('index', [\App\Http\Controllers\Web\User\DashboardController::class, 'index'])->name('web.user.dashboard.index');
    });

    Route::prefix('company')->group(function () {
        Route::get('index', [\App\Http\Controllers\Web\User\CompanyController::class, 'index'])->name('web.user.company.index');
        Route::get('detail/{id?}', [\App\Http\Controllers\Web\User\CompanyController::class, 'detail'])->name('web.user.company.detail');
    });

    Route::prefix('invoice')->group(function () {
        Route::get('index', [\App\Http\Controllers\Web\User\InvoiceController::class, 'index'])->name('web.user.invoice.index');
        Route::get('create', [\App\Http\Controllers\Web\User\InvoiceController::class, 'create'])->name('web.user.invoice.create');
        Route::get('edit/{id?}', [\App\Http\Controllers\Web\User\InvoiceController::class, 'edit'])->name('web.user.invoice.edit');
    });

    Route::prefix('eInvoice')->group(function () {
        Route::get('inbox', [\App\Http\Controllers\Web\User\EInvoiceController::class, 'inbox'])->name('web.user.eInvoice.inbox');
        Route::get('outbox', [\App\Http\Controllers\Web\User\EInvoiceController::class, 'outbox'])->name('web.user.eInvoice.outbox');
        Route::get('cancellationRequest', [\App\Http\Controllers\Web\User\EInvoiceController::class, 'cancellationRequest'])->name('web.user.eInvoice.cancellationRequest');
    });

    Route::prefix('order')->group(function () {
        Route::get('index', [\App\Http\Controllers\Web\User\OrderController::class, 'index'])->name('web.user.order.index');
    });

    Route::prefix('product')->group(function () {
        Route::get('index', [\App\Http\Controllers\Web\User\ProductController::class, 'index'])->name('web.user.product.index');
    });

    Route::prefix('transaction')->group(function () {
        Route::get('index', [\App\Http\Controllers\Web\User\TransactionController::class, 'index'])->name('web.user.transaction.index');
    });

    Route::prefix('safebox')->group(function () {
        Route::get('index', [\App\Http\Controllers\Web\User\SafeBoxController::class, 'index'])->name('web.user.safebox.index');
        Route::get('detail/{id?}', [\App\Http\Controllers\Web\User\SafeBoxController::class, 'detail'])->name('web.user.safebox.detail');
    });

    Route::prefix('report')->group(function () {
        Route::get('index', [\App\Http\Controllers\Web\User\ReportController::class, 'index'])->name('web.user.report.index');
    });

    Route::prefix('setting')->group(function () {
        Route::get('customer', [\App\Http\Controllers\Web\User\SettingController::class, 'customer'])->name('web.user.setting.customer');
        Route::get('customerUnit', [\App\Http\Controllers\Web\User\SettingController::class, 'customerUnit'])->name('web.user.setting.customerUnit');
        Route::get('stampAndLogo', [\App\Http\Controllers\Web\User\SettingController::class, 'stampAndLogo'])->name('web.user.setting.stampAndLogo');
        Route::get('customerTransactionCategory', [\App\Http\Controllers\Web\User\SettingController::class, 'customerTransactionCategory'])->name('web.user.setting.customerTransactionCategory');
        Route::get('user', [\App\Http\Controllers\Web\User\SettingController::class, 'user'])->name('web.user.setting.user');
    });

    Route::prefix('subscription')->group(function () {
        Route::get('index', [\App\Http\Controllers\Web\User\SubscriptionController::class, 'index'])->name('web.user.subscription.index')->withoutMiddleware('SubscriptionWeb');
    });
});
