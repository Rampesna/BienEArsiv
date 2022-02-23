<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Country;

class CountryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Country);
    }
}
