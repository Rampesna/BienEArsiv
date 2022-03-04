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
}
