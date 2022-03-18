<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Currency;

class CurrencyService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Currency);
    }
}
