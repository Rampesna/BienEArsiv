<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\TransactionType;

class TransactionTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new TransactionType);
    }
}
