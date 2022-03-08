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
}
