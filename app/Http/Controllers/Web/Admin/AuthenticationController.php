<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\AuthenticationController\OAuthRequest;
use App\Services\Eloquent\AdminService;
use Illuminate\Support\Facades\Crypt;

class AuthenticationController extends Controller
{
    private $adminService;


    public function __construct()
    {
        $this->adminService = new AdminService;
    }

    public function login()
    {
        if (auth()->check()) {
            return redirect()->route('web.admin.dashboard.index');
        }

        return view('admin.modules.auth.login.index');
    }

    public function logout()
    {
        if (auth()->check()) {
            auth()->logout();
        }

        return view('admin.modules.auth.login.index');
    }

    public function oAuth(OAuthRequest $request)
    {
        $admin = $this->adminService->getById(Crypt::decrypt($request->oAuth));

        if (!$admin) {
            return redirect()->route('web.admin.authentication.login');
        }

        auth()->guard('admin_web')->login($admin);

        return redirect()->route('web.admin.dashboard.index');
    }
}
