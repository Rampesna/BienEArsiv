<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Admin\SubscriptionController\CreateRequest;
use App\Http\Requests\Api\Admin\SubscriptionController\GetAllRequest;
use App\Services\Eloquent\CustomerSubscriptionService;
use App\Services\Eloquent\SubscriptionPaymentService;
use App\Services\Eloquent\SubscriptionService;
use App\Services\Eloquent\UserService;
use App\Traits\Response;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use Response;

    private $subscriptionService;
    private $subscriptionPaymentService;

    public function __construct()
    {
        $this->subscriptionService = new SubscriptionService;
        $this->subscriptionPaymentService = new SubscriptionPaymentService;
    }


    public function getAll(GetAllRequest $request)
    {
        return $this->success('Subscriptions', $this->subscriptionService->getAll());
    }

    public function create(CreateRequest $request)
    {
        $subscription = (new SubscriptionService)->getById($request->subscriptionId);
        $user = (new UserService())->getById($request->customerId);

        if (!$subscription) {
            return $this->error('Subscription not found', 404);
        }


        $subscriptionPaymentCheck = $this->subscriptionPaymentService->getByCustomerId($user->customer_id);
        if ($subscriptionPaymentCheck->count() > 0) {
            return $this->error('Bu kullanıcıya ait bir abonelik ödemesi bulunmaktadır', 400);
        }

        $orderId = 'order_' . strtotime('now');
        $this->subscriptionPaymentService->create(
            $user->customer_id,
            $subscription->id,
            $orderId,
            $subscription->price
        );

        $subscriptionPayment = $this->subscriptionPaymentService->getByOrderId($orderId);
        if ($subscriptionPayment) {
            $subscriptionPayment->approved = 1;
            $subscriptionPayment->save();
            (new CustomerSubscriptionService)->create(
                $subscriptionPayment->id,
                $subscriptionPayment->customer_id,
                $subscriptionPayment->subscription_id,
                $request->startDate,
                date('Y-m-d',strtotime('+' . $subscription->duration_of_days . ' days', strtotime($request->startDate))),
                1
            );
        }
        return $this->success('Abonelik ödemesi başarıyla oluşturuldu', [
            'subscriptionPayment' => $subscriptionPayment,
        ]);
    }
}
