<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\CompanyReportController\BalanceStatusRequest;
use App\Http\Requests\Api\User\CompanyReportController\ExtractRequest;
use App\Http\Requests\Api\User\CompanyReportController\TransactionRequest;
use App\Services\Eloquent\CompanyReportService;
use App\Services\Eloquent\CompanyService;
use App\Traits\Response;

class CompanyReportController extends Controller
{
    use Response;

    private $companyService;

    public function __construct()
    {
        $this->companyReportService = new CompanyReportService;
    }

    public function balanceStatus(BalanceStatusRequest $request)
    {
        return $this->success('Company balance status report', $this->companyReportService->balanceStatus(
            $request->user()->customer_id
        ));
    }

    public function extract(ExtractRequest $request)
    {
        $company = (new CompanyService)->getById($request->companyId);

        if (!$company || ($company->customer_id != $request->user()->customer_id)) {
            return $this->error('Company not found', 404);
        }

        return $this->success('Company extract report', $this->companyReportService->extract(
            $request->user()->customer_id,
            $company->id,
            $request->date
        ));
    }

    public function transaction(TransactionRequest $request)
    {
        $company = (new CompanyService)->getById($request->companyId);

        if (!$company || ($company->customer_id != $request->user()->customer_id)) {
            return $this->error('Company not found', 404);
        }

        return $this->success('Company transaction report', $this->companyReportService->transaction(
            $request->user()->customer_id,
            $company->id,
            $request->startDate,
            $request->endDate,
            $request->typeId
        ));
    }
}
