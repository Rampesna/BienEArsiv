<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\CustomerSubscriptionService;
use App\Http\Requests\Api\User\CustomerSubscriptionController\CheckRequest;
use App\Traits\Response;

class CustomerSubscriptionController extends Controller
{
    use Response;

    private $customerSubscriptionService;

    public function __construct()
    {
        $this->customerSubscriptionService = new CustomerSubscriptionService;
    }

    public function check(CheckRequest $request)
    {
        return $this->success('Check customer subscription', $request->user()->customer()->first()->subscription);
    }
}
