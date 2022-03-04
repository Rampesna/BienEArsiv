<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\InvoiceController\IndexRequest;
use App\Http\Requests\Api\User\InvoiceController\CountRequest;
use App\Http\Requests\Api\User\InvoiceController\GetByIdRequest;
use App\Http\Requests\Api\User\InvoiceController\CreateRequest;
use App\Http\Requests\Api\User\InvoiceController\UpdateRequest;
use App\Services\Eloquent\InvoiceService;
use App\Traits\Response;

class InvoiceController extends Controller
{
    use Response;

    private $invoiceService;

    public function __construct()
    {
        $this->invoiceService = new InvoiceService;
    }

    public function index(IndexRequest $request)
    {
        return $this->success('Invoices', $this->invoiceService->index(
            $request->user()->customer_id,
            $request->pageIndex,
            $request->pageSize,
            $request->companyId,
            $request->typeId,
            $request->direction,
            $request->datetimeStart,
            $request->datetimeEnd
        ));
    }

    public function count(CountRequest $request)
    {
        return $this->success('Invoices', $this->invoiceService->count(
            $request->user()->customer_id,
            $request->companyId
        ));
    }

    public function getById(GetByIdRequest $request)
    {
        $invoice = $this->invoiceService->getById($request->id);

        return !$invoice || ($invoice->customer_id != $request->user()->customer_id)
            ? $this->error('Invoice not found', 404)
            : $this->success('Invoice details', $invoice);
    }

    public function create(CreateRequest $request)
    {
        return $this->success('Invoice created successfully.', $this->invoiceService->create(
            $request->user()->customer_id,
            $request->taxNumber,
            $request->companyId,
            $request->typeId,
            $request->companyStatementDescription,
            $request->datetime,
            $request->number ?? $this->invoiceService->getNextInvoiceNumber($request->user()->customer_id),
            $request->vatIncluded,
            $request->waybillNumber,
            $request->waybillDatetime,
            $request->orderNumber,
            $request->orderDatetime,
            $request->price
        ));
    }

    public function update(UpdateRequest $request)
    {
        $invoice = $this->invoiceService->getById($request->id);

        if (!$invoice || ($invoice->customer_id != $request->user()->customer_id)) {
            return $this->error('Invoice not found', 404);
        }

        return $this->success('Invoice updated successfully.', $this->invoiceService->update(
            $request->id,
            $request->user()->customer_id,
            $request->taxNumber,
            $request->companyId,
            $request->typeId,
            $request->companyStatementDescription,
            $request->datetime,
            $request->number ?? $this->invoiceService->getNextInvoiceNumber($request->user()->customer_id),
            $request->vatIncluded,
            $request->waybillNumber,
            $request->waybillDatetime,
            $request->orderNumber,
            $request->orderDatetime,
            $request->price
        ));
    }
}
