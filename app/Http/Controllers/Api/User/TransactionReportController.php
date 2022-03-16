<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\TransactionReportController\GetRequest;
use App\Services\Eloquent\TransactionReportService;
use App\Traits\Response;

class TransactionReportController extends Controller
{
    use Response;

    private $transactionReportService;

    public function __construct()
    {
        $this->transactionReportService = new TransactionReportService;
    }

    public function get(GetRequest $request)
    {
        return $this->success('Transactions report', $this->transactionReportService->get(
            $request->user()->customer_id,
            $request->safeboxId,
            $request->direction,
            $request->categoryId,
            $request->startDate,
            $request->endDate
        ));
    }
}
