<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\ProductController\AllRequest;
use App\Http\Requests\Api\User\ProductController\IndexRequest;
use App\Http\Requests\Api\User\ProductController\GetByIdRequest;
use App\Http\Requests\Api\User\ProductController\CreateRequest;
use App\Http\Requests\Api\User\ProductController\UpdateRequest;
use App\Http\Requests\Api\User\ProductController\DeleteRequest;
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
        return !$product || $request->user()->customer_id != $product->customer_id
            ? $this->error('Product not found', 404)
            : $this->success('Product details', $product);
    }

    public function create(CreateRequest $request)
    {
        return $this->success('Product created successfully', $this->productService->create(
            $request->user()->customer_id,
            $request->code,
            $request->name,
            $request->unitId,
            $request->price,
            $request->vatRate
        ));
    }

    public function update(UpdateRequest $request)
    {
        $product = $this->productService->getById($request->id);

        if (!$product || ($request->user()->customer_id != $product->customer_id)) {
            return $this->error('Product not found', 404);
        }

        return $this->success('Product updated successfully', $this->productService->update(
            $product->id,
            $request->user()->customer_id,
            $request->code,
            $request->name,
            $request->unitId,
            $request->price,
            $request->vatRate
        ));
    }

    public function delete(DeleteRequest $request)
    {
        $product = $this->productService->getById($request->id);

        if (!$product || ($request->user()->customer_id != $product->customer_id)) {
            return $this->error('Product not found', 404);
        }

        return $this->success('Product deleted successfully', $this->productService->delete(
            $product->id
        ));
    }
}
