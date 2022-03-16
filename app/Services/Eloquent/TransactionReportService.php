<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Transaction;
use Barryvdh\DomPDF\Facade as PDF;

class TransactionReportService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Transaction);
    }

    /**
     * @param int $customerId
     * @param int $safeboxId
     * @param int $direction
     * @param int $categoryId
     * @param string $startDate
     * @param string $endDate
     */
    public function get(
        $customerId,
        $safeboxId,
        $direction,
        $categoryId,
        $startDate,
        $endDate
    )
    {
        $path = 'documents/customers/' . $customerId . '/transactions/report/get/' . date('Y_m_d_H_i_s');
        $fileName = date('d.m.Y', strtotime($startDate)) . '-' . date('d.m.Y', strtotime($endDate)) . ' Gelir & Gider Raporu' . '.pdf';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $filePath = base_path($path . '/' . $fileName);
        PDF::loadView('user.documents.report.transaction.get', [
            'transactions' => Transaction::with([
                'type'
            ])->where('customer_id', $customerId)
                ->where('safebox_id', $safeboxId)
                ->where('direction', $direction)
                ->where(function ($transactions) use ($categoryId) {
                    return $categoryId != 0 ? $transactions->where('category_id', $categoryId) : $transactions;
                })
                ->whereBetween('datetime', [$startDate, $endDate])
                ->get()->map(function ($transaction) {
                    return [
                        'date' => date('d.m.Y', strtotime($transaction->datetime)),
                        'type' => $transaction->type->name,
                        'amount' => number_format($transaction->amount, 2)
                    ];
                })->toArray()
        ], [], 'UTF-8')->save($filePath);

        return $path . '/' . $fileName;
    }
}
