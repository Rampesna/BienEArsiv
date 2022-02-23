<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\CompanyController\IndexRequest;
use App\Http\Requests\Api\User\CompanyController\GetByIdRequest;
use App\Http\Requests\Api\User\CompanyController\CreateRequest;
use App\Services\Eloquent\CompanyService;
use App\Traits\Response;

class CompanyController extends Controller
{
    use Response;

    private $companyService;

    public function __construct()
    {
        $this->companyService = new CompanyService;
    }

    public function index(IndexRequest $request)
    {
        return $this->success('Companies', $this->companyService->index(
            $request->user()->customer_id,
            $request->pageIndex,
            $request->pageSize,
            $request->keyword,
            $request->accountType,
            $request->balanceType
        ));
    }

    public function getById(GetByIdRequest $request)
    {
        $company = $this->companyService->getById($request->id);
        return $request->user()->customer_id == $company->customer_id
            ? $this->success('Company details', $company)
            : $this->error('Company not found', 404);
    }

    public function create(CreateRequest $request)
    {
        return $this->success('Company created successfully', $this->companyService->create(
            $request->user()->customer_id,
            $request->taxNumber,
            $request->taxOffice,
            $request->managerName,
            $request->managerSurname,
            $request->title,
            $request->email,
            $request->phone,
            $request->address,
            $request->countryId,
            $request->provinceId,
            $request->districtId,
            $request->postCode,
            $request->isCustomer,
            $request->isSupplier
        ));
    }
}
