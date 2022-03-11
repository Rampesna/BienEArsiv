<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('user.modules.subscription.index.index');
    }
}
