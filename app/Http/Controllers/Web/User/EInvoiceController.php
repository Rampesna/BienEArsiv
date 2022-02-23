<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;

class EInvoiceController extends Controller
{
    public function index()
    {
        return view('user.modules.eInvoice.index.index');
    }
}
