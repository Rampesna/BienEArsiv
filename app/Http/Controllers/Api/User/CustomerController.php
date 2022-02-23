<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\CustomerController\CreateRequest;
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

    public function create(CreateRequest $request)
    {
        return $this->success('Customer created successfully', $this->customerService->create(
            title: $request->title,
            email: $request->email,
        ));
    }
}
