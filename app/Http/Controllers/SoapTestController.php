<?php

namespace App\Http\Controllers;

use App\Services\Soap\Param\ParamService;
use Ramsey\Uuid\Uuid;

class SoapTestController extends Controller
{
    public function show()
    {
        $guid = '0c13d406-873b-403b-9c09-a5766840d98c';
        $orderId = strtotime('now');
        $paramService = new ParamService;
        return $paramService->PosPayment(
            $guid,
            creditCardHolderName: 'Albert Einstein',
            creditCardNumber: '4546711234567894',
            creditCardMonth: '12',
            creditCardYear: '2026',
            creditCardCvc: '000',
            creditCardHolderGsm: '5536720883',
            orderId: $orderId,
            orderDescription: 'test',
            numberOfInstallment: 1,
            transactionAmount: '149,90',
            totalAmount: '149,90',
            transactionType: 'NS',
            ipAddress: 'http://127.0.0.1',
            transactionId: '',
            refUrl: 'http://127.0.0.1',
            data1: 'test',
            data2: 'test',
            data3: 'test',
            data4: 'test',
            data5: 'test',
            data6: 'test',
            data7: 'test',
            data8: 'test',
            data9: 'test',
            data10: 'test'
        );
    }
}
