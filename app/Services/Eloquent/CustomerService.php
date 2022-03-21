<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Customer;
use App\Models\Eloquent\Unit;

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

        $units = Unit::all();
        $customerUnitService = new CustomerUnitService;

        foreach ($units as $unit) {
            $customerUnitService->create(
                $customer->id,
                $unit->code,
                $unit->name
            );
        }

        return $customer;
    }

    /**
     * @param int $id
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
     * @param int $wizard
     */
    public function update(
        int         $id,
        string      $title,
        string|null $taxOffice = null,
        string|null $taxNumber = null,
        string|null $gibPassword = null,
        string|null $phone = null,
        string|null $email = null,
        string|null $address = null,
        int|null    $provinceId = null,
        int|null    $districtId = null,
        string|null $logo = null,
        string|null $stamp = null,
        int         $wizard = 0
    )
    {
        $customer = Customer::find($id);
        $customer->title = $title;
        $customer->tax_office = $taxOffice;
        $customer->tax_number = $taxNumber;
        $customer->gib_password = $gibPassword;
        $customer->phone = $phone;
        $customer->email = $email;
        $customer->address = $address;
        $customer->province_id = $provinceId;
        $customer->district_id = $districtId;
        $customer->logo = $logo;
        $customer->stamp = $stamp;
        $customer->wizard = $wizard;
        $customer->save();

        return $customer;
    }

    /**
     * @param int $customerId
     * @param $stamp
     */
    public function updateStamp(
        $customerId,
        $stamp
    )
    {
        $customer = Customer::find($customerId);
        $path = 'customer/' . $customer->id . '/';
        $name = 'stamp.' . $stamp->getClientOriginalExtension();
        $stamp->move($path, $name);
        $customer->stamp = $path . $name;
        $customer->save();

        return $customer;
    }

    /**
     * @param int $customerId
     * @param $logo
     */
    public function updateLogo(
        $customerId,
        $logo
    )
    {
        $customer = Customer::find($customerId);
        $path = 'customer/' . $customer->id . '/';
        $name = 'stamp.' . $logo->getClientOriginalExtension();
        $logo->move($path, $name);
        $customer->logo = $path . $name;
        $customer->save();

        return $customer;
    }
}
