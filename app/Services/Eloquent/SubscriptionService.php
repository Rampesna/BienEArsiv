<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Subscription;

class SubscriptionService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Subscription);
    }
}
