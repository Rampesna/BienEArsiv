<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\InvoiceProduct;

class InvoiceProductService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new InvoiceProduct);
    }

    /**
     * @param int $invoiceId
     */
    public function getByInvoiceId(
        $invoiceId
    )
    {
        return InvoiceProduct::with([
            'product'
        ])->where('invoice_id', $invoiceId)->get();
    }

    /**
     * @param int $invoiceId
     * @param int $productId
     * @param float $quantity
     * @param int $unitId
     * @param float $unitPrice
     * @param float $vatRate
     * @param float $discountRate
     */
    public function create(
        $invoiceId,
        $productId,
        $quantity,
        $unitId,
        $unitPrice,
        $vatRate,
        $discountRate
    )
    {
        $invoiceProduct = new InvoiceProduct;
        $invoiceProduct->invoice_id = $invoiceId;
        $invoiceProduct->product_id = $productId;
        $invoiceProduct->quantity = $quantity;
        $invoiceProduct->unit_id = $unitId;
        $invoiceProduct->unit_price = $unitPrice;
        $invoiceProduct->vat_rate = $vatRate;
        $invoiceProduct->discount_rate = $discountRate;
        $invoiceProduct->save();

        return $invoiceProduct;
    }

    /**
     * @param int $id
     * @param int $invoiceId
     * @param int $productId
     * @param float $quantity
     * @param int $unitId
     * @param float $unitPrice
     * @param float $vatRate
     * @param float $discountRate
     */
    public function update(
        $id,
        $invoiceId,
        $productId,
        $quantity,
        $unitId,
        $unitPrice,
        $vatRate,
        $discountRate
    )
    {
        $invoiceProduct = InvoiceProduct::find($id);
        $invoiceProduct->invoice_id = $invoiceId;
        $invoiceProduct->product_id = $productId;
        $invoiceProduct->quantity = $quantity;
        $invoiceProduct->unit_id = $unitId;
        $invoiceProduct->unit_price = $unitPrice;
        $invoiceProduct->vat_rate = $vatRate;
        $invoiceProduct->discount_rate = $discountRate;
        $invoiceProduct->save();

        return $invoiceProduct;
    }
}
