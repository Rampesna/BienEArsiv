<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\InvoiceProductController\CreateRequest;
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
}
