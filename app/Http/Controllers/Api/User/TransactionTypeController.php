<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\TransactionTypeService;
use App\Traits\Response;

class TransactionTypeController extends Controller
{
    use Response;

    private $transactionTypeService;

    public function __construct()
    {
        $this->transactionTypeService = new TransactionTypeService;
    }

    public function getAll()
    {
        return $this->success('Transaction types', $this->transactionTypeService->getAll());
    }
}
