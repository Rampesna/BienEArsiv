<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\CustomerController\GetByIdRequest;
use App\Http\Requests\Api\User\CustomerController\CreateRequest;
use App\Http\Requests\Api\User\CustomerController\UpdateRequest;
use App\Http\Requests\Api\User\CustomerController\UpdateStampRequest;
use App\Http\Requests\Api\User\CustomerController\UpdateLogoRequest;
use App\Services\Eloquent\CustomerService;
use App\Traits\Response;

class CustomerController extends Controller
{
    use Response;

    private $customerService;

    public function __construct()
    {
        $this->customerService = new CustomerService;
    }

    public function getById(GetByIdRequest $request)
    {
        return $this->success('Customer details', $this->customerService->getById($request->user()->customer_id));
    }

    public function create(CreateRequest $request)
    {
        return $this->success('Customer created successfully', $this->customerService->create(
            title: $request->title,
            taxOffice: $request->taxOffice,
            taxNumber: $request->taxNumber,
            phone: $request->phone,
            email: $request->email,
            address: $request->address,
            provinceId: $request->provinceId,
            districtId: $request->districtId
        ));
    }

    public function update(UpdateRequest $request)
    {
        return $this->success('Customer updated successfully', $this->customerService->update(
            id: $request->user()->customer_id,
            title: $request->title,
            taxOffice: $request->taxOffice,
            taxNumber: $request->taxNumber,
            phone: $request->phone,
            email: $request->email,
            address: $request->address,
            provinceId: $request->provinceId,
            districtId: $request->districtId
        ));
    }

    public function updateStamp(UpdateStampRequest $request)
    {
        return $this->success('Customer stamp updated successfully', $this->customerService->updateStamp(
            $request->user()->customer_id,
            $request->file('stamp')
        ));
    }

    public function updateLogo(UpdateLogoRequest $request)
    {
        return $this->success('Customer logo updated successfully', $this->customerService->updateLogo(
            $request->user()->customer_id,
            $request->file('logo')
        ));
    }
}
