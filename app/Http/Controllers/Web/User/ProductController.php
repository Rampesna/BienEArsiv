<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return view('user.modules.product.index.index');
    }
}
