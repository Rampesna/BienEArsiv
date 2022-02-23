<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SafeBoxController extends Controller
{
    public function index()
    {
        return view('user.modules.safebox.index.index');
    }

    public function detail(Request $request)
    {
        return view('user.modules.safebox.detail.index', [
            'id' => $request->id,
        ]);
    }
}
