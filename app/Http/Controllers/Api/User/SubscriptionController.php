<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\SubscriptionService;
use App\Traits\Response;

class SubscriptionController extends Controller
{
    use Response;

    private $subscriptionService;

    public function __construct()
    {
        $this->subscriptionService = new SubscriptionService;
    }

    public function getAll()
    {
        return $this->success('Subscriptions', $this->subscriptionService->getAll());
    }
}
