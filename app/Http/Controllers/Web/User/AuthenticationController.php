<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\User\AuthenticationController\OAuthRequest;
use App\Services\Eloquent\PasswordResetService;
use App\Services\Eloquent\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AuthenticationController extends Controller
{
    private $userService;

    private $passwordResetService;

    public function __construct()
    {
        $this->userService = new UserService;
        $this->passwordResetService = new PasswordResetService;
    }

    public function login()
    {
        if (auth()->check()) {
            return redirect()->route('web.user.dashboard.index');
        }

        return view('user.modules.auth.login.index');
    }

    public function logout()
    {
        if (auth()->check()) {
            auth()->logout();
        }

        return view('user.modules.auth.login.index');
    }

    public function register()
    {
        return view('user.modules.auth.register.index');
    }

    public function forgotPassword()
    {
        return view('user.modules.auth.forgotPassword.index');
    }

    public function resetPassword(Request $request)
    {
        if (!$request->token) {
            abort(404);
        }

        $passwordReset = $this->passwordResetService->getByToken($request->token);

        if (!$passwordReset || $passwordReset->used == 1) {
            abort(404);
        }

        return view('user.modules.auth.resetPassword.index', [
            'token' => $request->token
        ]);
    }

    public function oAuth(OAuthRequest $request)
    {
        $user = $this->userService->getById(Crypt::decrypt($request->oAuth));

        if (!$user) {
            return redirect()->route('web.user.authentication.login');
        }

        auth()->login($user);

        return redirect()->route('web.user.dashboard.index');
    }
}
