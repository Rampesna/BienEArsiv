<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;

class WizardController extends Controller
{
    public function index()
    {
        if (auth()->user()->wizard()) {
            return redirect()->route('web.user.dashboard.index');
        }

        return view('user.modules.wizard.index.index');
    }
}
