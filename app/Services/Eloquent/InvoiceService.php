<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Invoice;
use App\Models\Eloquent\InvoiceProduct;

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
        $currencyId,
        $currency,
        $vatDiscountId,
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
        $invoice->currency_id = $currencyId;
        $invoice->currency = $currency;
        $invoice->vat_discount_id = $vatDiscountId;
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
     * @param int $currencyId
     * @param int $currency
     * @param int $vatDiscountId
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
        $currencyId,
        $currency,
        $vatDiscountId,
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
        $invoice->currency_id = $currencyId;
        $invoice->currency = $currency;
        $invoice->vat_discount_id = $vatDiscountId;
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
            'currencyName',
            'vatDiscount',
        ])->find($id);
        $invoiceProducts = InvoiceProduct::where('invoice_id', $invoice->id)->get();

        $gibService = new \App\Services\Rest\Gib\GibService;
        $gibService->setTestMode(true);
        $gibService->setCredentials($invoice->customer->tax_number, $invoice->customer->gib_password);
        $gibService->login();

        $gibInvoiceDate = date('d/m/Y', strtotime($invoice->datetime));
        $gibInvoiceHour = date('H:i:s', strtotime($invoice->datetime));
        $gibInvoiceCurrencyCode = $invoice->currencyName->code;
        $gibInvoiceCurrencyRate = $invoice->currency;
        $gibInvoiceInvoiceType = $invoice->vat_discount_id == 0 && $invoice->type_id != 10 ? $invoice->type->code : 'TEVKIFAT';
        $gibInvoiceWhichType = '5000/30000';
        $gibInvoiceTaxNumber = $invoice->company->tax_number;
        $gibInvoiceReceiverTitle = $invoice->company->title;
        $gibInvoiceReceiverName = $invoice->company->manager_name;
        $gibInvoiceReceiverSurname = $invoice->company->manager_surname;
        $gibInvoiceTaxOffice = $invoice->company->tax_office;
        $gibInvoiceCountry = $invoice->company->country?->name;
        $gibInvoiceAddress = $invoice->company->address;
        $gibInvoiceProvince = $invoice->company->province?->name;
        $gibInvoicePostCode = $invoice->company->post_code;
        $gibInvoicePhone = $invoice->company->phone;
        $gibInvoiceEmail = $invoice->company->email;
        $gibInvoiceType = 'İskonto';

        $baseTotal = 0;
        $servicesTotal = 0;
        $totalDiscount = 0;
        $calculatedVat = 0;
        $vatTotal = 0;
        $generalTotal = 0;

        $gibInvoiceProducts = [];

        foreach ($invoiceProducts as $invoiceProduct) {
            $rowBaseTotal = $invoiceProduct->unit_price * $invoiceProduct->quantity;
            $rowDiscount = $rowBaseTotal * $invoiceProduct->discount_rate / 100;
            $rowServicesTotal = $rowBaseTotal - $rowDiscount;
            $rowVatWithoutVatDiscount = $rowServicesTotal * $invoiceProduct->vat_rate / 100;
            $rowVatDiscount = $invoice->vat_discount_id == 0 ? 0 : ($rowVatWithoutVatDiscount * $invoice->vatDiscount->percent / 100);
            $rowVatTotal = $rowVatWithoutVatDiscount - $rowVatDiscount;

            $baseTotal += $rowBaseTotal;
            $servicesTotal += $rowServicesTotal;
            $totalDiscount += $rowDiscount;
            $calculatedVat += $rowVatTotal;
            $vatTotal += $rowVatTotal;
            $generalTotal += $rowServicesTotal + $rowVatTotal;

            $gibInvoiceProducts[] = [
                "malHizmet" => $invoiceProduct->product->name,
                "miktar" => $invoiceProduct->quantity,
                "birim" => $invoiceProduct->unit->code,
                "birimFiyat" => $invoiceProduct->unit_price,
                "fiyat" => $rowBaseTotal,
                "iskontoOrani" => $invoiceProduct->discount_rate,
                "iskontoTutari" => $rowDiscount,
                "iskontoNedeni" => "",
                "malHizmetTutari" => $rowServicesTotal,
                "kdvOrani" => $invoiceProduct->vat_rate,
                "vergiOrani" => $invoice->vat_discount_id == 0 ? 100 : $invoice->vatDiscount->percent,
                "kdvTutari" => $rowVatWithoutVatDiscount - $rowVatTotal,
                "vergininKdvTutari" => $rowVatWithoutVatDiscount - $rowVatTotal,
                "ozelMatrahTutari" => "0",
            ];
        }

        $invoiceToGibInvoice = [
            "belgeNumarasi" => "",
            "faturaTarihi" => $gibInvoiceDate,
            "saat" => $gibInvoiceHour,
            "paraBirimi" => $gibInvoiceCurrencyCode,
            "dovzTLkur" => $gibInvoiceCurrencyRate,
            "faturaTipi" => $gibInvoiceInvoiceType,
            "hangiTip" => $gibInvoiceWhichType,
            "vknTckn" => $gibInvoiceTaxNumber ?? "11111111111",
            "aliciUnvan" => $gibInvoiceReceiverTitle,
            "aliciAdi" => $gibInvoiceReceiverName,
            "aliciSoyadi" => $gibInvoiceReceiverSurname,
            "binaAdi" => "",
            "binaNo" => "",
            "kapiNo" => "",
            "kasabaKoy" => "",
            "vergiDairesi" => $gibInvoiceTaxOffice,
            "ulke" => $gibInvoiceCountry ?? "Türkiye",
            "bulvarcaddesokak" => $gibInvoiceAddress ?? "Türkiye",
            "mahalleSemtIlce" => "",
            "sehir" => $gibInvoiceProvince ?? "İstanbul",
            "postaKodu" => $gibInvoicePostCode ?? "34000",
            "tel" => $gibInvoicePhone ?? '5555555555',
            "fax" => "",
            "eposta" => $gibInvoiceEmail ?? '',
            "websitesi" => "",
            "iadeTable" => $invoice->type_id === 10 ? $gibInvoiceProducts : [],
            "ozelMatrahTutari" => "0",
            "ozelMatrahOrani" => 0,
            "ozelMatrahVergiTutari" => "0",
            "vergiCesidi" => $invoice->vat_discount_id == 0 ? "" : $invoice->vatDiscount->code,
            "tip" => $gibInvoiceType,
            "matrah" => $baseTotal,
            "malhizmetToplamTutari" => $servicesTotal,
            "toplamIskonto" => $totalDiscount,
            "hesaplanankdv" => $calculatedVat,
            "vergilerToplami" => $calculatedVat,
            "vergilerDahilToplamTutar" => $generalTotal,
            "odenecekTutar" => $generalTotal,
            "not" => $invoice->company_statement_description ?? '',
            "siparisNumarasi" => $invoice->order_number ?? '',
            "siparisTarihi" => $invoice->order_datetime ?? '',
            "irsaliyeNumarasi" => $invoice->waybill_number ?? '',
            "irsaliyeTarihi" => $invoice->waybill_datetime ?? '',
            "fisNo" => "",
            "fisTarihi" => "",
            "fisSaati" => " ",
            "fisTipi" => " ",
            "zRaporNo" => "",
            "okcSeriNo" => "",
            "malHizmetTable" => $invoice->type_id === 7 ? $gibInvoiceProducts : []
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
