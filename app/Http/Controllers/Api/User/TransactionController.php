<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\TransactionController\IndexRequest;
use App\Http\Requests\Api\User\TransactionController\CreateCreditRequest;
use App\Http\Requests\Api\User\TransactionController\CreateDebitRequest;
use App\Http\Requests\Api\User\TransactionController\CreateCollectionRequest;
use App\Http\Requests\Api\User\TransactionController\CreatePaymentRequest;
use App\Http\Requests\Api\User\TransactionController\CreateEarnRequest;
use App\Http\Requests\Api\User\TransactionController\CreateExpenseRequest;
use App\Services\Eloquent\CompanyService;
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

    public function createCredit(CreateCreditRequest $request)
    {
        $company = (new CompanyService)->getById($request->companyId);

        if (!$company) {
            return $this->error('Company not found', 404);
        }

        if ($request->user()->customer_id != $company->customer_id) {
            return $this->error('You are not allowed to do this action', 403);
        }

        return $this->success('Transaction created successfully', $this->transactionService->create(
            $request->user()->customer_id,
            $company->id,
            $request->datetime,
            5,
            '',
            $request->description,
            null,
            0,
            $request->amount
        ));
    }

    public function createDebit(CreateDebitRequest $request)
    {
        $company = (new CompanyService)->getById($request->companyId);

        if (!$company) {
            return $this->error('Company not found', 404);
        }

        if ($request->user()->customer_id != $company->customer_id) {
            return $this->error('You are not allowed to do this action', 403);
        }

        return $this->success('Transaction created successfully', $this->transactionService->create(
            $request->user()->customer_id,
            $company->id,
            $request->datetime,
            6,
            '',
            $request->description,
            null,
            1,
            $request->amount
        ));
    }

    public function createCollection(CreateCollectionRequest $request)
    {
        $company = (new CompanyService)->getById($request->companyId);

        if (!$company) {
            return $this->error('Company not found', 404);
        }

        if ($request->user()->customer_id != $company->customer_id) {
            return $this->error('You are not allowed to do this action', 403);
        }

        return $this->success('Transaction created successfully', $this->transactionService->create(
            $request->user()->customer_id,
            $company->id,
            $request->datetime,
            1,
            '',
            $request->description,
            $request->safeboxId,
            0,
            $request->amount
        ));
    }

    public function createPayment(CreatePaymentRequest $request)
    {
        $company = (new CompanyService)->getById($request->companyId);
        $safebox = (new SafeboxService)->getById($request->safeboxId);

        if (!$company) {
            return $this->error('Company not found', 404);
        }

        if (!$safebox) {
            return $this->error('Safebox not found', 404);
        }

        if (
            ($request->user()->customer_id != $company->customer_id) ||
            ($request->user()->customer_id != $safebox->customer_id)
        ) {
            return $this->error('You are not allowed to do this action', 403);
        }

        return $this->success('Transaction created successfully', $this->transactionService->create(
            $request->user()->customer_id,
            $company->id,
            $request->datetime,
            2,
            '',
            $request->description,
            $request->safeboxId,
            1,
            $request->amount
        ));
    }

    public function createEarn(CreateEarnRequest $request)
    {
        $safebox = (new SafeboxService)->getById($request->safeboxId);

        if (!$safebox) {
            return $this->error('Safebox not found', 404);
        }

        if ($request->user()->customer_id != $safebox->customer_id) {
            return $this->error('You are not allowed to do this action', 403);
        }

        return $this->success('Transaction created successfully', $this->transactionService->create(
            $request->user()->customer_id,
            null,
            $request->datetime,
            3,
            '',
            $request->description,
            $request->safeboxId,
            0,
            $request->amount
        ));
    }

    public function createExpense(CreateExpenseRequest $request)
    {
        $safebox = (new SafeboxService)->getById($request->safeboxId);

        if (!$safebox) {
            return $this->error('Safebox not found', 404);
        }

        if ($request->user()->customer_id != $safebox->customer_id) {
            return $this->error('You are not allowed to do this action', 403);
        }

        return $this->success('Transaction created successfully', $this->transactionService->create(
            $request->user()->customer_id,
            null,
            $request->datetime,
            4,
            '',
            $request->description,
            $request->safeboxId,
            1,
            $request->amount
        ));
    }
}
