<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\TransactionType;

class TransactionTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new TransactionType);
    }

    /**
     * @param int $direction
     * @param int $invoice
     */
    public function index(
        $direction,
        $invoice
    )
    {
        $transactionTypes = TransactionType::with([]);

        if ($direction) {
            $transactionTypes->where('direction', $direction);
        }

        if ($invoice) {
            $transactionTypes->where('invoice', $invoice);
        }

        return $transactionTypes->get();
    }
}
