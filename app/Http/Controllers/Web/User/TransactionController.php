<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function index()
    {
        return view('user.modules.transaction.index.index');
    }
}
