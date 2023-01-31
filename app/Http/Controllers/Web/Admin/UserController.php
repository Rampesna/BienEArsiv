<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.modules.user.index.index');
    }
}
