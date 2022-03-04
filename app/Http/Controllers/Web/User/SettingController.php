<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function customer()
    {
        return view('user.modules.setting.customer.index');
    }

    public function customerUnit()
    {
        return view('user.modules.setting.customerUnit.index');
    }

    public function stampAndLogo()
    {
        return view('user.modules.setting.stampAndLogo.index');
    }

    public function transactionCategory()
    {
        return view('user.modules.setting.transactionCategory.index');
    }

    public function user()
    {
        return view('user.modules.setting.user.index');
    }
}
