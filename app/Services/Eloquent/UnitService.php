<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Unit;

class UnitService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Unit);
    }
}
