<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\CustomerUnit;

class CustomerUnitService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new CustomerUnit);
    }

    /**
     * @param int $customerId
     */
    public function all(
        $customerId
    )
    {
        return CustomerUnit::where('customer_id', $customerId)->get();
    }

    /**
     * @param int $customerId
     * @param int $pageIndex
     * @param int $pageSize
     * @param string $keyword
     */
    public function index(
        $customerId,
        $pageIndex,
        $pageSize,
        $keyword
    )
    {
        $customerUnits = CustomerUnit::where('customer_id', $customerId);

        if ($keyword) {
            $customerUnits
                ->where('code', 'like', '%' . $keyword . '%')
                ->orWhere('name', 'like', '%' . $keyword . '%');
        }

        $totalCount = $customerUnits->count();
        return [
            'totalCount' => $totalCount,
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize,
            'customerUnits' => $customerUnits->skip($pageSize * $pageIndex)
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
        return CustomerUnit::find($id);
    }

    /**
     * @param int $customerId
     * @param string $code
     * @param string $name
     */
    public function create(
        $customerId,
        $code,
        $name,
    )
    {
        $customerUnit = new CustomerUnit;
        $customerUnit->customer_id = $customerId;
        $customerUnit->code = $code;
        $customerUnit->name = $name;
        $customerUnit->save();

        return $customerUnit;
    }

    /**
     * @param int $id
     * @param string $code
     * @param string $name
     */
    public function update(
        $id,
        $code,
        $name
    )
    {
        $customerUnit = CustomerUnit::find($id);
        $customerUnit->code = $code;
        $customerUnit->name = $name;
        $customerUnit->save();

        return $customerUnit;
    }

    /**
     * @param int $id
     */
    public function delete(
        $id
    )
    {
        return CustomerUnit::find($id)->delete();
    }
}
