<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\SubscriptionPayment;

class SubscriptionPaymentService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new SubscriptionPayment);
    }

    /**
     * @param string $orderId
     */
    public function getByOrderId(
        $orderId
    )
    {
        return SubscriptionPayment::where('order_id', $orderId)->first();
    }

    /**
     * @param int $customerId
     * @param int $subscriptionId
     * @param int $orderId
     * @param double $amount
     */
    public function create(
        $customerId,
        $subscriptionId,
        $orderId,
        $amount
    )
    {
        $subscriptionPayment = new SubscriptionPayment();
        $subscriptionPayment->customer_id = $customerId;
        $subscriptionPayment->subscription_id = $subscriptionId;
        $subscriptionPayment->order_id = $orderId;
        $subscriptionPayment->amount = $amount;
        $subscriptionPayment->save();

        return $subscriptionPayment;
    }
}
