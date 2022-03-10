<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;

class EInvoiceController extends Controller
{
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
}
