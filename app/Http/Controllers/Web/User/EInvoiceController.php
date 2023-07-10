<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\CustomerService;
use App\Services\Eloquent\InvoiceService;
use App\Services\Rest\Gib\GibService;
use Illuminate\Http\Request;

class EInvoiceController extends Controller
{
    private $gibService;

    public function __construct()
    {
        $this->gibService = new GibService;
    }

    public function inbox()
    {
        return view('user.modules.eInvoice.inbox.index');
    }

    public function outbox()
    {
        return view('user.modules.eInvoice.outbox.index');
    }

    public function cancellationRequest()
    {
        return view('user.modules.eInvoice.cancellationRequest.index');
    }

    public function invoice(Request $request)
    {
        return view('user.modules.eInvoice.invoice.index', [
            'invoice' => $this->gibService->getInvoiceHTML(
                $request->uuid,
                auth()->user()->customer->gib_token
            )
        ]);
    }

    public function invoicePdf(Request $request)
    {
        return $request;
    }
}
