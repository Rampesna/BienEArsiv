<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Safebox;

class SafeboxService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Safebox);
    }

    /**
     * @param int $customerId
     */
    public function all(
        $customerId
    )
    {
        return Safebox::where('customer_id', $customerId)->get();
    }

    /**
     * @param int $customerId
     * @param int $pageIndex
     * @param int $pageSize
     * @param string $keyword
     * @param int $safeboxType
     */
    public function index(
        $customerId,
        $pageIndex,
        $pageSize,
        $keyword,
        $safeboxType
    )
    {
        $safeboxes = Safebox::with([
            'type'
        ])->where('customer_id', $customerId);

        if ($keyword) {
            $safeboxes->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            });
        }

        if ($safeboxType != 0) {
            $safeboxes->where('type_id', $safeboxType);
        }

        $totalCount = $safeboxes->count();
        return [
            'totalCount' => $totalCount,
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize,
            'safeboxes' => $safeboxes->skip($pageSize * $pageIndex)
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
        return Safebox::find($id);
    }

    /**
     * @param int $customerId
     * @param int $typeId
     * @param string $name
     * @param string $accountNumber
     * @param string $branch
     * @param string $iban
     */
    public function create(
        $customerId,
        $typeId,
        $name,
        $accountNumber,
        $branch,
        $iban
    )
    {
        $safebox = new Safebox;
        $safebox->customer_id = $customerId;
        $safebox->type_id = $typeId;
        $safebox->name = $name;
        $safebox->account_number = $accountNumber;
        $safebox->branch = $branch;
        $safebox->iban = $iban;
        $safebox->save();

        return $safebox;
    }

    /**
     * @param int $id
     * @param int $customerId
     * @param int $typeId
     * @param string $name
     * @param string $accountNumber
     * @param string $branch
     * @param string $iban
     */
    public function update(
        $id,
        $customerId,
        $typeId,
        $name,
        $accountNumber,
        $branch,
        $iban
    )
    {
        $safebox = Safebox::find($id);
        $safebox->customer_id = $customerId;
        $safebox->type_id = $typeId;
        $safebox->name = $name;
        $safebox->account_number = $accountNumber;
        $safebox->branch = $branch;
        $safebox->iban = $iban;
        $safebox->save();

        return $safebox;
    }

    /**
     * @param int $id
     */
    public function delete(
        $id
    )
    {
        return Safebox::find($id)->delete();
    }

    /**
     * @param int $customerId
     * @param int|null $typeId
     */
    public function getTotalBalance(
        $customerId,
        $typeId = null
    )
    {
        $safeboxes = Safebox::where('customer_id', $customerId);

        if ($typeId) {
            $safeboxes->where('type_id', $typeId);
        }

        return $safeboxes->get()->sum('balance');
    }
}
