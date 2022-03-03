<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\CompanyController\AllRequest;
use App\Http\Requests\Api\User\CompanyController\IndexRequest;
use App\Http\Requests\Api\User\CompanyController\GetByIdRequest;
use App\Http\Requests\Api\User\CompanyController\CreateRequest;
use App\Http\Requests\Api\User\CompanyController\UpdateRequest;
use App\Http\Requests\Api\User\CompanyController\DeleteRequest;
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

    public function all(AllRequest $request)
    {
        return $this->success('Companies', $this->companyService->all(
            $request->user()->customer_id
        ));
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
        return !$company || ($request->user()->customer_id != $company->customer_id)
            ? $this->error('Company not found', 404)
            : $this->success('Company details', $company);
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

    public function update(UpdateRequest $request)
    {
        $company = $this->companyService->getById($request->id);

        if ($company->customer_id != $request->user()->customer_id) {
            return $this->error('Company not found', 404);
        }

        return $this->success('Company updated successfully', $this->companyService->update(
            $request->id,
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

    public function delete(DeleteRequest $request)
    {
        $company = $this->companyService->getById($request->id);

        if (!$company || ($request->user()->customer_id != $company->customer_id)) {
            return $this->error('Company not found', 404);
        }

        return $this->success('Company deleted successfully', $this->companyService->delete(
            $request->id
        ));
    }
}
