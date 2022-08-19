<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\WizardController\CompleteRequest;
use App\Services\Eloquent\CustomerService;
use App\Traits\Response;

class WizardController extends Controller
{
    use Response;

    private $customerService;

    public function __construct()
    {
        $this->customerService = new CustomerService;
    }

    public function complete(CompleteRequest $request)
    {
        return $this->success('Customer updated successfully', $this->customerService->update(
            id: $request->user()->customer_id,
            title: $request->title,
            taxOffice: $request->taxOffice,
            taxNumber: $request->taxNumber,
            gibCode: $request->gibCode,
            gibPassword: $request->gibPassword,
            phone: $request->phone,
            email: $request->email,
            address: $request->address,
            provinceId: $request->provinceId,
            districtId: $request->districtId,
            wizard: 1,
        ));
    }
}
