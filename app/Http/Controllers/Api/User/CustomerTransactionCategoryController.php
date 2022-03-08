<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\CustomerTransactionCategoryController\AllRequest;
use App\Http\Requests\Api\User\CustomerTransactionCategoryController\IndexRequest;
use App\Http\Requests\Api\User\CustomerTransactionCategoryController\GetByIdRequest;
use App\Http\Requests\Api\User\CustomerTransactionCategoryController\CreateRequest;
use App\Http\Requests\Api\User\CustomerTransactionCategoryController\UpdateRequest;
use App\Http\Requests\Api\User\CustomerTransactionCategoryController\DeleteRequest;
use App\Services\Eloquent\CustomerTransactionCategoryService;
use App\Traits\Response;

class CustomerTransactionCategoryController extends Controller
{
    use Response;

    private $customerTransactionCategoryService;

    public function __construct()
    {
        $this->customerTransactionCategoryService = new CustomerTransactionCategoryService;
    }

    public function all(AllRequest $request)
    {
        return $this->success('Customer transaction categorys', $this->customerTransactionCategoryService->all(
            $request->user()->customer_id
        ));
    }

    public function index(IndexRequest $request)
    {
        return $this->success('Customer transaction categories', $this->customerTransactionCategoryService->index(
            $request->user()->customer_id,
            $request->pageIndex,
            $request->pageSize,
            $request->keyword
        ));
    }

    public function getById(GetByIdRequest $request)
    {
        $customerTransactionCategory = $this->customerTransactionCategoryService->getById($request->id);
        return !$customerTransactionCategory || $request->user()->customer_id != $customerTransactionCategory->customer_id
            ? $this->error('Customer transaction category not found', 404)
            : $this->success('Customer transaction category details', $customerTransactionCategory);
    }

    public function create(CreateRequest $request)
    {
        return $this->success('Customer transaction category created successfully', $this->customerTransactionCategoryService->create(
            $request->user()->customer_id,
            $request->name
        ));
    }

    public function update(UpdateRequest $request)
    {
        $customerTransactionCategory = $this->customerTransactionCategoryService->getById($request->id);

        if (!$customerTransactionCategory || ($customerTransactionCategory->customer_id != $request->user()->customer_id)) {
            return $this->error('Customer transaction category not found', 404);
        }

        return $this->success('Customer transaction category updated successfully', $this->customerTransactionCategoryService->update(
            $customerTransactionCategory->id,
            $request->name
        ));
    }

    public function delete(DeleteRequest $request)
    {
        $customerTransactionCategory = $this->customerTransactionCategoryService->getById($request->id);

        if (!$customerTransactionCategory || ($customerTransactionCategory->customer_id != $request->user()->customer_id)) {
            return $this->error('Customer transaction category not found', 404);
        }

        return $this->success('Customer transaction category created successfully', $this->customerTransactionCategoryService->delete(
            $customerTransactionCategory->id
        ));
    }
}
