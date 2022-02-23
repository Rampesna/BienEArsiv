<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\SafeboxType;

class SafeboxTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new SafeboxType);
    }
}
