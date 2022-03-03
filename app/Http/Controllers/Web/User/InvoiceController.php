<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('user.modules.invoice.index.index');
    }

    public function create()
    {
        return view('user.modules.invoice.create.index');
    }

    public function edit($id)
    {
        return view('user.modules.invoice.edit.index', [
            'id' => $id,
        ]);
    }
}
