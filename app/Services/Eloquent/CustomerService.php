<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Customer;

class CustomerService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Customer);
    }

    /**
     * @param string $title
     * @param string|null $taxOffice
     * @param string|null $taxNumber
     * @param string|null $phone
     * @param string|null $email
     * @param string|null $address
     * @param int|null $provinceId
     * @param int|null $districtId
     * @param string|null $logo
     * @param string|null $stamp
     */
    public function create(
        string      $title,
        string|null $taxOffice = null,
        string|null $taxNumber = null,
        string|null $phone = null,
        string|null $email = null,
        string|null $address = null,
        int|null    $provinceId = null,
        int|null    $districtId = null,
        string|null $logo = null,
        string|null $stamp = null
    )
    {
        $customer = new Customer();
        $customer->title = $title;
        $customer->tax_office = $taxOffice;
        $customer->tax_number = $taxNumber;
        $customer->phone = $phone;
        $customer->email = $email;
        $customer->address = $address;
        $customer->province_id = $provinceId;
        $customer->district_id = $districtId;
        $customer->logo = $logo;
        $customer->stamp = $stamp;
        $customer->save();

        return $customer;
    }
}
