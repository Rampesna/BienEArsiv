<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\CustomerTransactionCategory;

class CustomerTransactionCategoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new CustomerTransactionCategory);
    }

    /**
     * @param int $customerId
     */
    public function all(
        $customerId
    )
    {
        return CustomerTransactionCategory::where('customer_id', $customerId)->get();
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
        $customerTransactionCategories = CustomerTransactionCategory::where('customer_id', $customerId);

        if ($keyword) {
            $customerTransactionCategories->where('name', 'like', '%' . $keyword . '%');
        }

        $totalCount = $customerTransactionCategories->count();
        return [
            'totalCount' => $totalCount,
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize,
            'customerTransactionCategories' => $customerTransactionCategories->skip($pageSize * $pageIndex)
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
        return CustomerTransactionCategory::find($id);
    }

    /**
     * @param int $customerId
     * @param string $name
     */
    public function create(
        $customerId,
        $name,
    )
    {
        $customerTransactionCategory = new CustomerTransactionCategory;
        $customerTransactionCategory->customer_id = $customerId;
        $customerTransactionCategory->name = $name;
        $customerTransactionCategory->save();

        return $customerTransactionCategory;
    }

    /**
     * @param int $id
     * @param string $name
     */
    public function update(
        $id,
        $name
    )
    {
        $customerTransactionCategory = CustomerTransactionCategory::find($id);
        $customerTransactionCategory->name = $name;
        $customerTransactionCategory->save();

        return $customerTransactionCategory;
    }

    /**
     * @param int $id
     */
    public function delete(
        $id
    )
    {
        return CustomerTransactionCategory::find($id)->delete();
    }
}
