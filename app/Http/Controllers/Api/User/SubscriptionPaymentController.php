<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\SubscriptionPaymentService;
use App\Http\Requests\Api\User\SubscriptionPaymentController\CreateRequest;
use App\Services\Eloquent\SubscriptionService;
use App\Services\Eloquent\CustomerSubscriptionService;
use App\Services\Soap\Param\ParamService;
use App\Traits\Response;
use Illuminate\Http\Request;

class SubscriptionPaymentController extends Controller
{
    use Response;

    private $unitService;

    public function __construct()
    {
        $this->subscriptionPaymentService = new SubscriptionPaymentService;
    }

    public function create(CreateRequest $request)
    {
        $subscription = (new SubscriptionService)->getById($request->subscriptionId);

        if (!$subscription) {
            return $this->error('Subscription not found', 404);
        }

        $paramService = new ParamService;
        $orderId = 'order_' . strtotime('now');
        $paramServicePosPaymentResponse = $paramService->PosPayment(
            $request->creditCardHolderName,
            $request->creditCardNumber,
            $request->creditCardMonth,
            $request->creditCardYear,
            $request->creditCardCvc,
            str_replace(' ', '', str_replace('(', '', str_replace(')', '', str_replace('-', '', $request->user()->customer->phone)))),
            $orderId,
            '',
            1,
            number_format($subscription->price, 2, ',', ''),
            number_format($subscription->price, 2, ',', ''),
            '3D'
        );

        if ($paramServicePosPaymentResponse->Pos_OdemeResult->Sonuc == "1") {
            return $this->success('Subscription payment created successfully', [
                'subscriptionPayment' => $this->subscriptionPaymentService->create(
                    $request->user()->customer_id,
                    $subscription->id,
                    $orderId,
                    $subscription->price
                ),
                'paramServicePosPaymentResponse' => $paramServicePosPaymentResponse,
            ]);
        } else {
            return $this->error($paramServicePosPaymentResponse, 400);
        }
    }

    public function successUrl(Request $request)
    {
        $subscriptionPayment = $this->subscriptionPaymentService->getByOrderId($request->TURKPOS_RETVAL_Siparis_ID);
        $subscription = (new SubscriptionService)->getById($subscriptionPayment->subscription_id);

        if ($subscriptionPayment) {
            $subscriptionPayment->approved = 1;
            $subscriptionPayment->save();

            (new CustomerSubscriptionService)->create(
                $subscriptionPayment->id,
                $subscriptionPayment->customer_id,
                $subscriptionPayment->subscription_id,
                date('Y-m-d'),
                date('Y-m-d', strtotime('+' . $subscription->duration_of_days . ' days')),
                1
            );
        }

        return redirect()->route('web.user.subscription.index')->with([
            'type' => 'success',
            'message' => 'Ödeme İşleminiz Başarıyla Gerçekleşmiştir.'
        ]);
    }

    public function failureUrl(Request $request)
    {
        $subscriptionPayment = $this->subscriptionPaymentService->getByOrderId($request->orderId);

        if ($subscriptionPayment) {
            $subscriptionPayment->approved = 0;
            $subscriptionPayment->save();
        }
    }
}
