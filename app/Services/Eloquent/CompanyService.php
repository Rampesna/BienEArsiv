<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Company;
use Rap2hpoutre\FastExcel\FastExcel;

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
     * @param int $customerId
     */
    public function report(
        $customerId
    )
    {
        $companies = Company::where('customer_id', $customerId)->get();
        $fastExcel = new FastExcel;
        $fastExcel->data(
            $companies->map(function ($company) {
                return [
                    'Firma' => $company->title,
                    'Vergi Dairesi' => $company->tax_office,
                    'Vergi Numarası' => $company->tax_number,
                    'E-posta Adresi' => $company->email,
                    'Bakiye' => $company->balance,
                    'Durum' => intval($company->balance) > 0 ? 'Borçluyuz' : (intval($company->balance) < 0 ? 'Alacaklıyız' : 'Mutabık')
                ];
            })
        );
        $path = 'documents/customers/' . $customerId . '/companies/report/' . date('Y_m_d_H_i_s');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $filePath = base_path($path . '/Cariler.xlsx');
        $fastExcel->export($filePath);

        return $path . '/Cariler.xlsx';
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
     * @param string $taxNumber
     */
    public function getByTaxNumber(
        string $taxNumber
    )
    {
        return Company::where('tax_number', $taxNumber)->first();
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

    /**
     * @param int $id
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
    public function update(
        $id,
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
        $company = Company::find($id);

        if (!$company) {
            return false;
        }

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

    /**
     * @param int $id
     */
    public function delete(
        $id
    )
    {
        return Company::find($id)->delete();
    }
}
