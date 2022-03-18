<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\VatDiscountService;
use App\Traits\Response;

class VatDiscountController extends Controller
{
    use Response;

    private $vatDiscountService;

    public function __construct()
    {
        $this->vatDiscountService = new VatDiscountService;
    }

    public function getAll()
    {
        return $this->success('Vat discounts', $this->vatDiscountService->getAll());
    }
}
