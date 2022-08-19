<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserController\IndexRequest;
use App\Http\Requests\Api\User\UserController\CreateRequest;
use App\Http\Requests\Api\User\UserController\UpdateRequest;
use App\Http\Requests\Api\User\UserController\DeleteRequest;
use App\Http\Requests\Api\User\UserController\LoginRequest;
use App\Http\Requests\Api\User\UserController\GetByIdRequest;
use App\Http\Requests\Api\User\UserController\UpdateThemeRequest;
use App\Http\Requests\Api\User\UserController\SendPasswordResetEmailRequest;
use App\Http\Requests\Api\User\UserController\ResetPasswordRequest;
use App\Mail\User\ForgotPasswordEmail;
use App\Services\Eloquent\PasswordResetService;
use App\Services\Eloquent\UserService;
use App\Traits\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    use Response;

    private $userService;

    private $passwordResetService;

    public function __construct()
    {
        $this->userService = new UserService;
        $this->passwordResetService = new PasswordResetService;
    }

    public function login(LoginRequest $request)
    {
        $response = $this->userService->login($request->email, $request->password);
        return is_array($response) ? $this->success('User logged in successfully', $response) : $this->error('An error occurred', $response);
    }

    public function index(IndexRequest $request)
    {
        return $this->success('Users', $this->userService->index(
            $request->user()->customer_id,
            $request->pageIndex,
            $request->pageSize
        ));
    }

    public function create(CreateRequest $request)
    {
        return $this->success('User created successfully', $this->userService->create(
            $request->customerId,
            $request->name,
            $request->surname,
            $request->email,
            $request->password
        ));
    }

    public function update(UpdateRequest $request)
    {
        $user = $this->userService->getById($request->id);

        if (!$user || ($user->customer_id != $request->user()->customer_id)) {
            return $this->error('User not found', 404);
        }

        return $this->success('User updated successfully', $this->userService->update(
            $user->id,
            $request->name,
            $request->surname,
            $request->phone
        ));
    }

    public function delete(DeleteRequest $request)
    {
        if ($request->id == $request->user()->id) {
            return $this->error('You can not delete yourself', 403);
        }

        $user = $this->userService->getById($request->id);

        if (!$user || ($user->customer_id != $request->user()->customer_id)) {
            return $this->error('User not found', 404);
        }

        $count = $this->userService->count($request->user()->customer_id);

        if ($count <= 1) {
            return $this->error('You can not delete the last user', 401);
        }

        return $this->success('User deleted successfully', $this->userService->delete(
            $user->id
        ));
    }

    public function getById(GetByIdRequest $request)
    {
        if ($request->user()->id == $request->id) return $this->success('User details', $request->user());

        $user = $this->userService->getById($request->id);

        return !$user || ($user->customer_id != $request->user()->customer_id)
            ? $this->error('User not found', 404)
            : $this->success('User details', $user);
    }

    public function updateTheme(UpdateThemeRequest $request)
    {
        return $this->success('Theme updated successfully', $this->userService->updateTheme($request->user()->id, $request->theme));
    }

    public function sendPasswordResetEmail(SendPasswordResetEmailRequest $request)
    {
        $user = $this->userService->getByEmail($request->email);

        if (!$user) {
            return $this->error('User not found', 404);
        }

        $checkPasswordReset = $this->passwordResetService->checkPasswordReset(
            'App\\Models\\Eloquent\\User',
            $user->id,
            date('Y-m-d H:i:s', strtotime('-1 hour'))
        );

        if ($checkPasswordReset == true) {
            return $this->error('You can not send another password reset email for the same user within an hour', 406);
        }

        $passwordReset = $this->passwordResetService->create(
            'App\\Models\\Eloquent\\User',
            $user->id
        );

        Mail::to($user->email)->send(new ForgotPasswordEmail($passwordReset->token));

        return $this->success('Password reset email sent successfully', null);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        if (!$passwordReset = $this->passwordResetService->getByToken($request->resetPasswordToken)) return $this->error('Password reset token not found', 404);
        if (!$user = $this->userService->getById($passwordReset->relation_id)) return $this->error('User not found', 404);

        $this->passwordResetService->setUsed($passwordReset);
        $this->userService->updatePassword(
            $user,
            Hash::make($request->newPassword)
        );

        return $this->success('Password reset successfully', null);
    }
}
