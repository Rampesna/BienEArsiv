<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserController\CreateRequest;
use App\Http\Requests\Api\User\UserController\LoginRequest;
use App\Http\Requests\Api\User\UserController\ShowRequest;
use App\Http\Requests\Api\User\UserController\UpdateThemeRequest;
use App\Services\Eloquent\UserService;
use App\Traits\Response;

class UserController extends Controller
{
    use Response;

    private $userService;

    public function __construct()
    {
        $this->userService = new UserService;
    }

    public function login(LoginRequest $request)
    {
        $response = $this->userService->login($request->email, $request->password);
        return is_array($response) ? $this->success('User logged in successfully', $response) : $this->error('An error occurred', $response);
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

    public function show(ShowRequest $request)
    {
        $user = $this->userService->getById($request->id);
        return gettype($user) == 'integer' ?
            $this->error('', $user) :
            $this->success('User details', $user);
    }

    public function updateTheme(UpdateThemeRequest $request)
    {
        return $this->success('Theme updated successfully', $this->userService->updateTheme($request->user()->id, $request->theme));
    }
}
