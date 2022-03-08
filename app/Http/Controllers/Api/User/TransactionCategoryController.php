<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\TransactionCategoryService;
use App\Traits\Response;

class TransactionCategoryController extends Controller
{
    use Response;

    private $unitService;

    public function __construct()
    {
        $this->transactionCategoryService = new TransactionCategoryService;
    }

    public function getAll()
    {
        return $this->success('Transaction Categories', $this->transactionCategoryService->getAll());
    }
}
