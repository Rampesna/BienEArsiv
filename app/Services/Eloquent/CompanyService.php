<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Company;

class CompanyService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Company);
    }

    /**
     * @param int $customerId
     */
    public function all(
        $customerId
    )
    {
        return Company::where('customer_id', $customerId)->get();
    }

    /**
     * @param int $customerId
     * @param int $pageIndex
     * @param int $pageSize
     * @param string $keyword
     * @param int $accountType
     * @param int $balanceType
     */
    public function index(
        $customerId,
        $pageIndex,
        $pageSize,
        $keyword,
        $accountType,
        $balanceType
    )
    {
        $companies = Company::where('customer_id', $customerId);

        if ($keyword) {
            $companies->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%')
                    ->orWhere('phone', 'like', '%' . $keyword . '%')
                    ->orWhere('tax_number', 'like', '%' . $keyword . '%');
            });
        }

        if ($accountType == 1) {
            $companies->where('is_customer', 1);
        } elseif ($accountType == 2) {
            $companies->where('is_supplier', 1);
        }

        if ($balanceType == 1) {
            $companies->where('balance', '>', 0);
        } elseif ($balanceType == 2) {
            $companies->where('balance', '<', 0);
        }

        $totalCount = $companies->count();
        return [
            'totalCount' => $totalCount,
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize,
            'companies' => $companies->skip($pageSize * $pageIndex)
                ->take($pageSize)
                ->get()
        ];
    }

    /**
     * @param int $id
     */
    public function getById(
        $id
    )
    {
        return Company::find($id);
    }

    /**
     * @param int $customerId
     * @param string $taxNumber
     * @param string $taxOffice
     * @param string $managerName
     * @param string $managerSurname
     * @param string $title
     * @param string $email
     * @param string $phone
     * @param string $address
     * @param string $countryId
     * @param string $provinceId
     * @param string $districtId
     * @param string $postCode
     * @param string $isCustomer
     * @param string $isSupplier
     */
    public function create(
        $customerId,
        $taxNumber,
        $taxOffice,
        $managerName,
        $managerSurname,
        $title,
        $email,
        $phone,
        $address,
        $countryId,
        $provinceId,
        $districtId,
        $postCode,
        $isCustomer,
        $isSupplier
    )
    {
        $company = new Company;
        $company->customer_id = $customerId;
        $company->tax_number = $taxNumber;
        $company->tax_office = $taxOffice;
        $company->manager_name = $managerName;
        $company->manager_surname = $managerSurname;
        $company->title = $title;
        $company->email = $email;
        $company->phone = $phone;
        $company->address = $address;
        $company->country_id = $countryId;
        $company->province_id = $provinceId;
        $company->district_id = $districtId;
        $company->post_code = $postCode;
        $company->is_customer = $isCustomer;
        $company->is_supplier = $isSupplier;
        $company->save();

        return $company;
    }
}
