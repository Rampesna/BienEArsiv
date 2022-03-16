<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Company;
use Rap2hpoutre\FastExcel\FastExcel;
use Barryvdh\DomPDF\Facade as PDF;

class CompanyReportService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Company);
    }

    /**
     * @param int $customerId
     */
    public function balanceStatus(
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
        $path = 'documents/customers/' . $customerId . '/companies/report/balanceStatus/' . date('Y_m_d_H_i_s');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $filePath = base_path($path . '/Cariler.xlsx');
        $fastExcel->export($filePath);

        return $path . '/Cariler.xlsx';
    }

    /**
     * @param int $customerId
     * @param int $companyId
     * @param string $date
     */
    public function extract(
        $customerId,
        $companyId,
        $date
    )
    {
        $company = Company::find($companyId);
        $path = 'documents/customers/' . $customerId . '/companies/report/extract/' . date('Y_m_d_H_i_s');
        $fileName = $company->title . ' ' . date('d.m.Y', strtotime($date)) . ' Ekstre Raporu' . '.pdf';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $filePath = base_path($path . '/' . $fileName);
        PDF::loadView('user.documents.report.company.extract', [
            'transactions' => $company->transactions()->with([
                'type'
            ])->where('datetime', '<=', $date)->get()->map(function ($transaction) {
                return [
                    'date' => date('d.m.Y', strtotime($transaction->datetime)),
                    'type' => $transaction->type->name,
                    'amount' => number_format($transaction->amount, 2)
                ];
            })->toArray()
        ], [], 'UTF-8')->save($filePath);

        return $path . '/' . $fileName;
    }

    /**
     * @param int $customerId
     * @param int $companyId
     * @param string $dateStart
     * @param string $dateEnd
     * @param int $typeId
     */
    public function transaction(
        $customerId,
        $companyId,
        $startDate,
        $endDate,
        $typeId
    )
    {
        $company = Company::find($companyId);
        $path = 'documents/customers/' . $customerId . '/companies/report/transaction/' . date('Y_m_d_H_i_s');
        $fileName = $company->title . ' ' . date('d.m.Y', strtotime($startDate)) . '-' . date('d.m.Y', strtotime($endDate)) . ' Hareket Raporu' . '.pdf';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $filePath = base_path($path . '/' . $fileName);
        PDF::loadView('user.documents.report.company.transaction', [
            'transactions' => $company->transactions()->with([
                'type'
            ])->whereBetween('datetime', [
                $startDate,
                $endDate
            ])->where(function ($transactions) use ($typeId) {
                return $typeId != 0 ? $transactions->where('type_id', $typeId) : $transactions;
            })->get()->map(function ($transaction) {
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
