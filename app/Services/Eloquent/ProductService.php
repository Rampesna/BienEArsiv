<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Product;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductService extends BaseService
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
        return Product::with([
            'unit'
        ])->where('customer_id', $customerId)->get();
    }

    /**
     * @param int $customerId
     * @param int $pageIndex
     * @param int $pageSize
     * @param string $keyword
     */
    public function index(
        $customerId,
        $pageIndex,
        $pageSize,
        $keyword
    )
    {
        $products = Product::with([
            'unit'
        ])->where('customer_id', $customerId);

        if ($keyword) {
            $products->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%')
                    ->orWhere('price', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        $totalCount = $products->count();
        return [
            'totalCount' => $totalCount,
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize,
            'products' => $products->skip($pageSize * $pageIndex)
                ->take($pageSize)
                ->get()
        ];
    }

    /**
     * @param int $customerId
     */
    public function report(
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

    /**
     * @param int $id
     */
    public function getById(
        $id
    )
    {
        return Product::find($id);
    }

    /**
     * @param int $customerId
     * @param string $code
     * @param string $name
     * @param int $unitId
     * @param double $price
     * @param double $vatRate
     */
    public function create(
        $customerId,
        $code,
        $name,
        $unitId,
        $price,
        $vatRate
    )
    {
        $product = new Product;
        $product->customer_id = $customerId;
        $product->code = $code;
        $product->name = $name;
        $product->unit_id = $unitId;
        $product->price = $price;
        $product->vat_Rate = $vatRate;
        $product->save();

        return $product;
    }

    /**
     * @param int $id
     * @param int $customerId
     * @param string $code
     * @param string $name
     * @param int $unitId
     * @param double $price
     * @param double $vatRate
     */
    public function update(
        $id,
        $customerId,
        $code,
        $name,
        $unitId,
        $price,
        $vatRate
    )
    {
        $product = Product::find($id);
        $product->customer_id = $customerId;
        $product->code = $code;
        $product->name = $name;
        $product->unit_id = $unitId;
        $product->price = $price;
        $product->vat_Rate = $vatRate;
        $product->save();

        return $product;
    }

    /**
     * @param int $id
     */
    public function delete(
        $id
    )
    {
        return Product::find($id)->delete();
    }
}
