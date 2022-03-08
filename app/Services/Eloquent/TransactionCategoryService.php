<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\TransactionCategory;

class TransactionCategoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new TransactionCategory);
    }
}
