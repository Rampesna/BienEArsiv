<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\EInvoiceController\GetInvoiceHTMLRequest;
use App\Http\Requests\Api\User\EInvoiceController\GetInvoicePdfRequest;
use App\Http\Requests\Api\User\EInvoiceController\GetInvoicesRequest;
use App\Services\Rest\Gib\GibService;
use App\Traits\Response;

class EInvoiceController extends Controller
{
    use Response;

    private $gibService;

    public function __construct()
    {
        $this->gibService = new GibService;
    }

    public function getInvoices(GetInvoicesRequest $request)
    {
        $this->gibService->setTestMode(true);
        $this->gibService->setCredentials($request->user()->customer->tax_number, $request->user()->customer->gib_password);
        $this->gibService->login();
        return $this->success('eInvoices', $this->gibService->getInvoices(
            date('d/m/Y', strtoTime($request->dateStart)),
            date('d/m/Y', strtoTime($request->dateEnd))
        ));
    }

    public function getInvoiceHTML(GetInvoiceHTMLRequest $request)
    {
        $this->gibService->setTestMode(true);
        $this->gibService->setCredentials($request->user()->customer->tax_number, $request->user()->customer->gib_password);
        $this->gibService->login();

        return $this->success('eInvoice HTML', $this->gibService->getInvoiceHTML(
            $request->uuid
        ));
    }

    public function getInvoicePDF(GetInvoicePdfRequest $request)
    {
        $this->gibService->setTestMode(true);
        $this->gibService->setCredentials($request->user()->customer->tax_number, $request->user()->customer->gib_password);
        $this->gibService->login();

        $this->gibService->getInvoicePDF(
            $request->uuid
        );
    }
}
