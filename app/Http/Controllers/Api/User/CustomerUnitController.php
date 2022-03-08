<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\CustomerUnitController\AllRequest;
use App\Http\Requests\Api\User\CustomerUnitController\IndexRequest;
use App\Http\Requests\Api\User\CustomerUnitController\GetByIdRequest;
use App\Http\Requests\Api\User\CustomerUnitController\CreateRequest;
use App\Http\Requests\Api\User\CustomerUnitController\UpdateRequest;
use App\Http\Requests\Api\User\CustomerUnitController\DeleteRequest;
use App\Services\Eloquent\CustomerUnitService;
use App\Traits\Response;

class CustomerUnitController extends Controller
{
    use Response;

    private $customerUnitService;

    public function __construct()
    {
        $this->customerUnitService = new CustomerUnitService;
    }

    public function all(AllRequest $request)
    {
        return $this->success('Customer units', $this->customerUnitService->all(
            $request->user()->customer_id
        ));
    }

    public function index(IndexRequest $request)
    {
        return $this->success('Customer units', $this->customerUnitService->index(
            $request->user()->customer_id,
            $request->pageIndex,
            $request->pageSize,
            $request->keyword,
        ));
    }

    public function getById(GetByIdRequest $request)
    {
        $customerUnit = $this->customerUnitService->getById($request->id);
        return !$customerUnit || $request->user()->customer_id != $customerUnit->customer_id
            ? $this->error('Customer unit not found', 404)
            : $this->success('Customer unit details', $customerUnit);
    }

    public function create(CreateRequest $request)
    {
        return $this->success('Customer unit created successfully', $this->customerUnitService->create(
            $request->user()->customer_id,
            $request->code,
            $request->name
        ));
    }

    public function update(UpdateRequest $request)
    {
        $customerUnit = $this->customerUnitService->getById($request->id);

        if (!$customerUnit || ($customerUnit->customer_id != $request->user()->customer_id)) {
            return $this->error('Customer unit not found', 404);
        }

        return $this->success('Customer unit updated successfully', $this->customerUnitService->update(
            $customerUnit->id,
            $request->code,
            $request->name
        ));
    }

    public function delete(DeleteRequest $request)
    {
        $customerUnit = $this->customerUnitService->getById($request->id);

        if (!$customerUnit || ($customerUnit->customer_id != $request->user()->customer_id)) {
            return $this->error('Customer unit not found', 404);
        }

        return $this->success('Customer unit created successfully', $this->customerUnitService->delete(
            $customerUnit->id
        ));
    }
}
