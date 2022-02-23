<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Product;

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
        return Product::where('customer_id', $customerId)->get();
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
                $query->where('name', 'like', '%' . $keyword . '%');
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
     */
    public function create(
        $customerId,
    )
    {
        $product = new Product;
        $product->customer_id = $customerId;
        $product->save();

        return $product;
    }
}
