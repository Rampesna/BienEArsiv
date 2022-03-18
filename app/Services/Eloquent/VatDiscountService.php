<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\VatDiscount;

class VatDiscountService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new VatDiscount);
    }
}
