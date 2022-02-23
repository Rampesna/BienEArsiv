<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return view('user.modules.order.index.index');
    }
}
