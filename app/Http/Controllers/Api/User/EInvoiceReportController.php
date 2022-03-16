<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\EInvoiceReportController\OutboxRequest;
use App\Services\Rest\Gib\GibService;
use App\Traits\Response;
use Rap2hpoutre\FastExcel\FastExcel;

class EInvoiceReportController extends Controller
{
    use Response;

    private $gibService;

    public function __construct()
    {
        $this->gibService = new GibService;
    }

    public function outbox(OutboxRequest $request)
    {
        $this->gibService->setCredentials($request->user()->customer->tax_number, $request->user()->customer->gib_password);
        $this->gibService->login();

        $eInvoices = $this->gibService->outbox(
            date('d/m/Y', strtoTime($request->startDate)),
            date('d/m/Y', strtoTime($request->endDate))
        );

        $fastExcel = new FastExcel;
        $fastExcel->data(
            collect($eInvoices)->map(function ($eInvoice) {
                return [
                    'Alıcı Ünvan' => $eInvoice->aliciUnvanAdSoyad,
                    'Alıcı VKN/TCKN' => $eInvoice->aliciVknTckn,
                    'Belge Numarası' => $eInvoice->belgeNumarasi,
                    'Belge Tarihi' => $eInvoice->belgeTarihi,
                    'Belge Türü' => $eInvoice->belgeTuru,
                    'Ettn' => $eInvoice->ettn,
                    'Durum' => $eInvoice->onayDurumu,
                ];
            })
        );
        $path = 'documents/customers/' . $request->user()->customer_id . '/eInvoices/report/outbox';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $fileName = date('d.m.Y', strtotime($request->startDate)) . '-' . date('d.m.Y', strtotime($request->startDate)) . ' Giden e-Arşiv Faturalar.xlsx';
        $filePath = base_path($path . '/' . $fileName);
        $fastExcel->export($filePath);

        return $this->success('filePath', $path . '/' . $fileName);
    }
}
