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

Route::get('test', function () {
    $invoice = (new \App\Services\Eloquent\InvoiceService)->getByIdWith(1);
    $invoiceProducts = (new \App\Services\Eloquent\InvoiceProductService)->getByInvoiceId(1);

    $eInvoice = [
        "belgeNumarasi" => "", // Zorunlu değil
        "faturaTarihi" => date('d/m/Y', strtotime($invoice->datetime)), // Zorunlu değil
        "saat" => date('H:i:s', strtotime($invoice->datetime)),
        "paraBirimi" => "TRY",
        "dovzTLkur" => "0",
        "faturaTipi" => "SATIS",
        "hangiTip" => "5000/30000",
        "vknTckn" => "11111111111",
        "aliciUnvan" => $invoice->company->title,
        "aliciAdi" => $invoice->company->manager_name,
        "aliciSoyadi" => $invoice->company->manager_surname,
        "binaAdi" => "", // Zorunlu değil
        "binaNo" => "", // Zorunlu değil
        "kapiNo" => "", // Zorunlu değil
        "kasabaKoy" => "", // Zorunlu değil
        "vergiDairesi" => $invoice->company->tax_office,
        "ulke" => $invoice->company->country?->name,
        "bulvarcaddesokak" => $invoice->company->address,
        "mahalleSemtIlce" => "", // Zorunlu değil
        "sehir" => $invoice->company->province?->name,
        "postaKodu" => $invoice->company->post_code, // Zorunlu değil
        "tel" => $invoice->company->phone, // Zorunlu değil
        "fax" => "", // Zorunlu değil
        "eposta" => $invoice->company->email, // Zorunlu değil
        "websitesi" => "", // Zorunlu değil
        "iadeTable" => [], // Zorunlu değil
        "ozelMatrahTutari" => "0", // Zorunlu değil
        "ozelMatrahOrani" => 0, // Zorunlu değil
        "ozelMatrahVergiTutari" => "0", // Zorunlu değil
        "vergiCesidi" => " ", // Zorunlu değil
        "tip" => "İskonto",
        "matrah" => array_sum($invoiceProducts->map(function ($invoiceProduct) {
            return $invoiceProduct->quantity * $invoiceProduct->unit_price;
        })->toArray()),
        "malhizmetToplamTutari" => array_sum($invoiceProducts->map(function ($invoiceProduct) {
            return $invoiceProduct->quantity * $invoiceProduct->unit_price;
        })->toArray()),
        "toplamIskonto" => "0",
        "hesaplanankdv" => array_sum($invoiceProducts->map(function ($invoiceProduct) {
            return $invoiceProduct->quantity * $invoiceProduct->unit_price / $invoiceProduct->vat_rate;
        })->toArray()),
        "vergilerToplami" => array_sum($invoiceProducts->map(function ($invoiceProduct) {
            return $invoiceProduct->quantity * $invoiceProduct->unit_price / $invoiceProduct->vat_rate;
        })->toArray()),
        "vergilerDahilToplamTutar" => array_sum($invoiceProducts->map(function ($invoiceProduct) {
            return ($invoiceProduct->quantity * $invoiceProduct->unit_price) + ($invoiceProduct->quantity * $invoiceProduct->unit_price / $invoiceProduct->vat_rate);
        })->toArray()),
        "odenecekTutar" => 118,
        "not" => "", // Zorunlu değil
        "siparisNumarasi" => "", // Zorunlu değil
        "siparisTarihi" => "", // Zorunlu değil
        "irsaliyeNumarasi" => "", // Zorunlu değil
        "irsaliyeTarihi" => "", // Zorunlu değil
        "fisNo" => "", // Zorunlu değil
        "fisTarihi" => "", // Zorunlu değil
        "fisSaati" => " ", // Zorunlu değil
        "fisTipi" => " ", // Zorunlu değil
        "zRaporNo" => "", // Zorunlu değil
        "okcSeriNo" => "", // Zorunlu değil
        "malHizmetTable" => $invoiceProducts->map(function ($invoiceProduct) {
            return [
                "malHizmet" => $invoiceProduct->product->name,
                "miktar" => $invoiceProduct->quantity,
                "birim" => $invoiceProduct->unit->name,
                "birimFiyat" => $invoiceProduct->unit_price,
                "fiyat" => $invoiceProduct->quantity * $invoiceProduct->unit_price,
                "iskontoOrani" => 0,
                "iskontoTutari" => "0",
                "iskontoNedeni" => "",
                "malHizmetTutari" => ($invoiceProduct->quantity * $invoiceProduct->unit_price) + ($invoiceProduct->quantity * $invoiceProduct->unit_price / $invoiceProduct->vat_rate),
                "kdvOrani" => $invoiceProduct->vat_rate,
                "vergiOrani" => 0,
                "kdvTutari" => $invoiceProduct->quantity * $invoiceProduct->unit_price / $invoiceProduct->vat_rate,
                "vergininKdvTutari" => "0",
                "ozelMatrahTutari" => "0", //zorunlu
            ];
        })->toArray()
    ];

    $inv = new \App\Helpers\InvoiceManager\Models\Invoice;
    $inv->mapWithTurkishKeys($eInvoice);

//    return dd($inv);

    $client = new App\Helpers\InvoiceManager\InvoiceManager;
    $client->setDebugMode(true)->setTestCredentials()->connect()->setInvoice($inv)->createDraftBasicInvoice();

    return $client->getInvoicesFromAPI('04/03/2022','04/03/2022');


});

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
    'auth'
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
        Route::get('index', [\App\Http\Controllers\Web\User\EInvoiceController::class, 'index'])->name('web.user.eInvoice.index');
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
        Route::get('transactionCategory', [\App\Http\Controllers\Web\User\SettingController::class, 'transactionCategory'])->name('web.user.setting.transactionCategory');
        Route::get('user', [\App\Http\Controllers\Web\User\SettingController::class, 'user'])->name('web.user.setting.user');
    });
});
