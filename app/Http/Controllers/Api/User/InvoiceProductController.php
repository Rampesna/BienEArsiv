<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\InvoiceProductController\GetByInvoiceIdRequest;
use App\Http\Requests\Api\User\InvoiceProductController\CreateRequest;
use App\Http\Requests\Api\User\InvoiceProductController\UpdateRequest;
use App\Services\Eloquent\InvoiceProductService;
use App\Services\Eloquent\InvoiceService;
use App\Services\Eloquent\ProductService;
use App\Traits\Response;

class InvoiceProductController extends Controller
{
    use Response;

    private $invoiceProductService;

    public function __construct()
    {
        $this->invoiceProductService = new InvoiceProductService;
    }

    public function getByInvoiceId(GetByInvoiceIdRequest $request)
    {
        $invoice = (new InvoiceService)->getById($request->invoiceId);

        if (!$invoice || ($invoice->customer_id != $request->user()->customer_id)) {
            return $this->responseNotFound('Invoice not found', 404);
        }

        return $this->success('Invoice products', $this->invoiceProductService->getByInvoiceId($request->invoiceId));
    }

    public function create(CreateRequest $request)
    {
        $invoice = (new InvoiceService)->getById($request->invoiceId);
        $product = (new ProductService)->getById($request->productId);

        if (!$invoice) {
            return $this->error('Invoice not found', 404);
        }

        if (!$product) {
            return $this->error('Product not found', 404);
        }

        if (
            $request->user()->customer_id != $invoice->customer_id ||
            $request->user()->customer_id != $product->customer_id
        ) {
            return $this->error('You are not allowed to do this action', 403);
        }

        return $this->success('Invoice product created successfully.', $this->invoiceProductService->create(
            $invoice->id,
            $product->id,
            $request->quantity,
            $request->unitId,
            $request->unitPrice,
            $request->vatRate
        ));
    }

    public function update(UpdateRequest $request)
    {
        $invoiceProduct = $this->invoiceProductService->getById($request->id);
        $invoice = (new InvoiceService)->getById($request->invoiceId);
        $product = (new ProductService)->getById($request->productId);

        if (!$invoiceProduct) {
            return $this->error('Invoice product not found', 404);
        }

        if (!$invoice) {
            return $this->error('Invoice not found', 404);
        }

        if (!$product) {
            return $this->error('Product not found', 404);
        }

        if (
            $request->user()->customer_id != $invoice->customer_id ||
            $request->user()->customer_id != $product->customer_id
        ) {
            return $this->error('You are not allowed to do this action', 403);
        }

        return $this->success('Invoice product updated successfully.', $this->invoiceProductService->update(
            $request->id,
            $invoice->id,
            $product->id,
            $request->quantity,
            $request->unitId,
            $request->unitPrice,
            $request->vatRate
        ));
    }
}
