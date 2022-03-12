<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\CustomerSubscription;

class CustomerSubscriptionService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new CustomerSubscription);
    }


    /**
     * @param int $paymentId
     * @param int $customerId
     * @param int $subscriptionId
     * @param int $subscriptionStartDate
     * @param int $subscriptionExpiryDate
     * @param int $isActive
     */
    public function create(
        $paymentId,
        $customerId,
        $subscriptionId,
        $subscriptionStartDate,
        $subscriptionExpiryDate,
        $isActive
    )
    {
        $customerSubscription = new CustomerSubscription;
        $customerSubscription->payment_id = $paymentId;
        $customerSubscription->customer_id = $customerId;
        $customerSubscription->subscription_id = $subscriptionId;
        $customerSubscription->subscription_start_date = $subscriptionStartDate;
        $customerSubscription->subscription_expiry_date = $subscriptionExpiryDate;
        $customerSubscription->is_active = $isActive;
        $customerSubscription->save();

        return $customerSubscription;
    }

    /**
     * @param int $customerId
     */
    public function check(
        $customerId
    )
    {

    }
}
