<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\TransactionController\IndexRequest;
use App\Http\Requests\Api\User\TransactionController\CreateRequest;
use App\Http\Requests\Api\User\TransactionController\AllRequest;
use App\Http\Requests\Api\User\TransactionController\CountRequest;
use App\Services\Eloquent\CompanyService;
use App\Services\Eloquent\InvoiceService;
use App\Services\Eloquent\SafeboxService;
use App\Services\Eloquent\TransactionService;
use App\Traits\Response;

class TransactionController extends Controller
{
    use Response;

    private $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService;
    }

    public function all(AllRequest $request)
    {
        return $this->success('Transactions', $this->transactionService->all(
            $request->user()->customer_id
        ));
    }

    public function count(CountRequest $request)
    {
        return $this->success('Transactions', $this->transactionService->count(
            $request->user()->customer_id
        ));
    }

    public function index(IndexRequest $request)
    {
        return $this->success('Transactions', $this->transactionService->index(
            $request->user()->customer_id,
            $request->pageIndex,
            $request->pageSize,
            $request->companyId,
            $request->typeId,
            $request->safeboxId,
            $request->direction,
            $request->datetimeStart,
            $request->datetimeEnd,
            $request->amountMin,
            $request->amountMax
        ));
    }

    public function create(CreateRequest $request)
    {
        if ($request->companyId) {
            $company = (new CompanyService)->getById($request->companyId);

            if (!$company) {
                return $this->error('Company not found', 404);
            }

            if ($request->user()->customer_id != $company->customer_id) {
                return $this->error('You are not allowed to do this action', 403);
            }
        }

        if ($request->safeboxId) {
            $safebox = (new SafeboxService)->getById($request->safeboxId);

            if (!$safebox) {
                return $this->error('Safebox not found', 404);
            }

            if ($request->user()->customer_id != $safebox->customer_id) {
                return $this->error('You are not allowed to do this action', 403);
            }
        }

        if ($request->invoiceId) {
            $invoice = (new InvoiceService)->getById($request->invoiceId);

            if (!$invoice) {
                return $this->error('Invoice not found', 404);
            }

            if ($request->user()->customer_id != $invoice->customer_id) {
                return $this->error('You are not allowed to do this action', 403);
            }
        }

        return $this->success('Transaction created successfully', $this->transactionService->create(
            $request->user()->customer_id,
            $request->companyId,
            $request->invoiceId,
            $request->datetime,
            $request->typeId,
            $request->receiptNumber,
            $request->description,
            $request->safeboxId,
            $request->direction,
            $request->amount
        ));
    }
}
