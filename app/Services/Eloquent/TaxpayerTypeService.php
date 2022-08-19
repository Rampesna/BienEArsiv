<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\TaxpayerType;

class TaxpayerTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new TaxpayerType);
    }
}
