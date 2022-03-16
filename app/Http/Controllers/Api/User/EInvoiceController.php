<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\EInvoiceController\GetInvoiceHTMLRequest;
use App\Http\Requests\Api\User\EInvoiceController\GetInvoicePdfRequest;
use App\Http\Requests\Api\User\EInvoiceController\OutboxRequest;
use App\Http\Requests\Api\User\EInvoiceController\InboxRequest;
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

    public function outbox(OutboxRequest $request)
    {
        $this->gibService->setCredentials($request->user()->customer->tax_number, $request->user()->customer->gib_password);
        $this->gibService->login();
        return $this->success('eInvoices', $this->gibService->outbox(
            date('d/m/Y', strtoTime($request->dateStart)),
            date('d/m/Y', strtoTime($request->dateEnd))
        ));
    }

    public function inbox(InboxRequest $request)
    {
        $this->gibService->setCredentials($request->user()->customer->tax_number, $request->user()->customer->gib_password);
        $this->gibService->login();
        return $this->success('eInvoices', $this->gibService->inbox(
            date('d/m/Y', strtoTime($request->dateStart)),
            date('d/m/Y', strtoTime($request->dateEnd))
        ));
    }

    public function getInvoiceHTML(GetInvoiceHTMLRequest $request)
    {
        $this->gibService->setCredentials($request->user()->customer->tax_number, $request->user()->customer->gib_password);
        $this->gibService->login();

        return $this->success('eInvoice HTML', $this->gibService->getInvoiceHTML(
            $request->uuid
        ));
    }

    public function getInvoicePDF(GetInvoicePdfRequest $request)
    {
        $this->gibService->setCredentials($request->user()->customer->tax_number, $request->user()->customer->gib_password);
        $this->gibService->login();

        $this->gibService->getInvoicePDF(
            $request->uuid
        );
    }
}
