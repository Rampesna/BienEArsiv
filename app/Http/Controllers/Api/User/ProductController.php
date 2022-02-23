<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\ProductController\AllRequest;
use App\Http\Requests\Api\User\ProductController\IndexRequest;
use App\Http\Requests\Api\User\ProductController\GetByIdRequest;
use App\Http\Requests\Api\User\ProductController\CreateRequest;
use App\Services\Eloquent\ProductService;
use App\Traits\Response;

class ProductController extends Controller
{
    use Response;

    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService;
    }

    public function all(AllRequest $request)
    {
        return $this->success('Products', $this->productService->all(
            $request->user()->customer_id
        ));
    }

    public function index(IndexRequest $request)
    {
        return $this->success('Products', $this->productService->index(
            $request->user()->customer_id,
            $request->pageIndex,
            $request->pageSize,
            $request->keyword,
        ));
    }

    public function getById(GetByIdRequest $request)
    {
        $product = $this->productService->getById($request->id);
        return $request->user()->customer_id == $product->customer_id
            ? $this->success('Product details', $product)
            : $this->error('Product not found', 404);
    }

    public function create(CreateRequest $request)
    {
        return $this->success('Product created successfully', $this->productService->create(
            $request->user()->customer_id,
        ));
    }
}
