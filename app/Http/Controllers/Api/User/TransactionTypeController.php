<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\TransactionTypeController\IndexRequest;
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

    public function index(IndexRequest $request)
    {
        return $this->success('Transaction types', $this->transactionTypeService->index(
            $request->direction,
            $request->invoice
        ));
    }
}
