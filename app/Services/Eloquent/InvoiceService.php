<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Invoice;

class InvoiceService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Invoice);
    }

    /**
     * @param int $id
     */
    public function getByIdWith(
        $id
    )
    {
        return Invoice::with([
            'type',
            'status',
            'company',
            'products'
        ])->find($id);
    }

    /**
     * @param int $customerId
     * @param int $pageIndex
     * @param int $pageSize
     * @param int|null $companyId
     * @param int|null $typeId
     * @param int|null $direction
     * @param string|null $datetimeStart
     * @param string|null $datetimeEnd
     */
    public function index(
        $customerId,
        $pageIndex = 1,
        $pageSize = 5,
        $companyId = null,
        $typeId = null,
        $direction = null,
        $datetimeStart = null,
        $datetimeEnd = null
    )
    {
        $invoices = Invoice::with([
            'type',
            'status',
            'company'
        ])->where('customer_id', $customerId);

        if ($companyId) {
            $invoices->where('company_id', $companyId);
        }

        if ($typeId) {
            $invoices->where('type_id', $typeId);
        }

        if ($direction) {
            $invoices->where('direction', $direction);
        }

        if ($datetimeStart) {
            $invoices->where('datetime', '>=', $datetimeStart);
        }

        if ($datetimeEnd) {
            $invoices->where('datetime', '<=', $datetimeEnd);
        }

        return [
            'totalCount' => $invoices->count(),
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize,
            'invoices' => $invoices->orderBy('created_at', 'desc')->skip($pageIndex * $pageSize)->take($pageSize)->get(),
        ];
    }

    /**
     * @param int $companyId
     */
    public function count(
        $companyId
    )
    {
        return Invoice::where('company_id', $companyId)->count();
    }

    /**
     * @param int $customerId
     * @param string|null $taxNumber
     * @param int $companyId
     * @param int $typeId
     * @param string|null $companyStatementDescription
     * @param string $datetime
     * @param string|null $number
     * @param int $vatIncluded
     * @param string|null $waybillNumber
     * @param string|null $waybillDatetime
     * @param string|null $orderDatetime
     * @param string|null $orderDatetime
     * @param float|null $price
     */
    public function create(
        $customerId,
        $taxNumber,
        $companyId,
        $typeId,
        $companyStatementDescription,
        $datetime,
        $number,
        $vatIncluded,
        $waybillNumber,
        $waybillDatetime,
        $orderNumber,
        $orderDatetime,
        $price
    )
    {
        $invoice = new Invoice;
        $invoice->customer_id = $customerId;
        $invoice->tax_number = $taxNumber;
        $invoice->company_id = $companyId;
        $invoice->type_id = $typeId;
        $invoice->company_statement_description = $companyStatementDescription;
        $invoice->datetime = $datetime;
        $invoice->number = $number;
        $invoice->vat_included = $vatIncluded;
        $invoice->waybill_number = $waybillNumber;
        $invoice->waybill_datetime = $waybillDatetime;
        $invoice->order_number = $orderNumber;
        $invoice->order_datetime = $orderDatetime;
        $invoice->price = $price;
        $invoice->save();

        return $invoice;
    }

    /**
     * @param int $id
     * @param int $customerId
     * @param string|null $taxNumber
     * @param int $companyId
     * @param int $typeId
     * @param string|null $companyStatementDescription
     * @param string $datetime
     * @param string|null $number
     * @param int $vatIncluded
     * @param string|null $waybillNumber
     * @param string|null $waybillDatetime
     * @param string|null $orderDatetime
     * @param string|null $orderDatetime
     * @param float|null $price
     */
    public function update(
        $id,
        $customerId,
        $taxNumber,
        $companyId,
        $typeId,
        $companyStatementDescription,
        $datetime,
        $number,
        $vatIncluded,
        $waybillNumber,
        $waybillDatetime,
        $orderNumber,
        $orderDatetime,
        $price
    )
    {
        $invoice = Invoice::find($id);
        $invoice->customer_id = $customerId;
        $invoice->tax_number = $taxNumber;
        $invoice->company_id = $companyId;
        $invoice->type_id = $typeId;
        $invoice->company_statement_description = $companyStatementDescription;
        $invoice->datetime = $datetime;
        $invoice->number = $number;
        $invoice->vat_included = $vatIncluded;
        $invoice->waybill_number = $waybillNumber;
        $invoice->waybill_datetime = $waybillDatetime;
        $invoice->order_number = $orderNumber;
        $invoice->order_datetime = $orderDatetime;
        $invoice->price = $price;
        $invoice->save();

        (new TransactionService)->getByInvoiceId($id)->map(function ($transaction) use ($invoice) {
            $transaction->amount = $invoice->price;
            $transaction->save();
        });

        return $invoice;
    }

    /**
     * @param int $customerId
     */
    public function getNextInvoiceNumber(
        $customerId
    )
    {
        $lastInvoice = Invoice::where('customer_id', $customerId)->orderBy('created_at', 'desc')->first();
        return $lastInvoice ? $lastInvoice->number + 1 : 1;
    }

    /**
     * @param int $id
     */
    public function sendToGib(
        $id
    )
    {
        $invoice = Invoice::with([
            'company' => function ($company) {
                $company->with([
                    'country'
                ]);
            },
        ])->find($id);
        $invoiceProducts = $invoice->products()->get();

        $gibService = new \App\Services\Rest\Gib\GibService;
        $gibService->setTestMode(true);
        $gibService->setCredentials($invoice->customer->tax_number, $invoice->customer->gib_password);
        $gibService->login();

        $invoiceToGibInvoice = [
            "belgeNumarasi" => "", // Zorunlu değil
            "faturaTarihi" => date('d/m/Y', strtotime($invoice->datetime)), // Zorunlu değil
            "saat" => date('H:i:s', strtotime($invoice->datetime)),
            "paraBirimi" => "TRY",
            "dovzTLkur" => "0",
            "faturaTipi" => "SATIS",
            "hangiTip" => "5000/30000",
            "vknTckn" => $invoice->tax_number ?? "11111111111",
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
            "odenecekTutar" => array_sum($invoiceProducts->map(function ($invoiceProduct) {
                return ($invoiceProduct->quantity * $invoiceProduct->unit_price) + ($invoiceProduct->quantity * $invoiceProduct->unit_price / $invoiceProduct->vat_rate);
            })->toArray()),
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
        $gibInvoice = new \App\Services\Rest\Gib\Models\GibInvoice;
        $gibInvoice->mapWithTurkishKeys($invoiceToGibInvoice);

        $uuid = $gibInvoice->getUuid();

        $gibService->createInvoice($gibInvoice);

        $invoice->uuid = $uuid;
        $invoice->status_id = 2;
        $invoice->locked = 1;
        return $invoice->save();
    }
}
