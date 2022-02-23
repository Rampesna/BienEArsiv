<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return view('user.modules.company.index.index');
    }

    public function detail(Request $request)
    {
        return view('user.modules.company.detail.index', [
            'id' => $request->id,
        ]);
    }
}
