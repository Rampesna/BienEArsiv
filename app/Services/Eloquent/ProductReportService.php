<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Product;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductReportService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Product);
    }

    /**
     * @param int $customerId
     */
    public function all(
        $customerId
    )
    {
        $products = Product::with([
            'unit'
        ])->where('customer_id', $customerId)->get();
        $fastExcel = new FastExcel;
        $fastExcel->data(
            $products->map(function ($product) {
                return [
                    'Ürün Kodu' => $product->code,
                    'Ürün Adı' => $product->name,
                    'Birim' => $product->unit->name,
                    'Fiyat' => number_format($product->price, 2),
                    'KDV Oranı' => $product->vat_rate . '%',
                    'Açıklamalar' => $product->description
                ];
            })
        );
        $path = 'documents/customers/' . $customerId . '/products/report/' . date('Y_m_d_H_i_s');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $filePath = base_path($path . '/Ürünler.xlsx');
        $fastExcel->export($filePath);

        return $path . '/Ürünler.xlsx';
    }
}
