<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\AdminController\IndexRequest;
use App\Http\Requests\Api\Admin\AdminController\CreateRequest;
use App\Http\Requests\Api\Admin\AdminController\UpdateRequest;
use App\Http\Requests\Api\Admin\AdminController\DeleteRequest;
use App\Http\Requests\Api\Admin\AdminController\LoginRequest;
use App\Http\Requests\Api\Admin\AdminController\GetByIdRequest;
use App\Http\Requests\Api\Admin\AdminController\UpdateThemeRequest;
use App\Services\Eloquent\AdminService;
use App\Traits\Response;

class AdminController extends Controller
{
    use Response;

    private $adminService;

    public function __construct()
    {
        $this->adminService = new AdminService;
    }

    public function login(LoginRequest $request)
    {
        $response = $this->adminService->login($request->email, $request->password);
        return is_array($response) ? $this->success('Admin logged in successfully', $response) : $this->error('An error occurred', $response);
    }

    public function index(IndexRequest $request)
    {
        return $this->success('Admins', $this->adminService->index(
            $request->pageIndex,
            $request->pageSize
        ));
    }

    public function create(CreateRequest $request)
    {
        return $this->success('Admin created successfully', $this->adminService->create(
            $request->name,
            $request->email,
            $request->password
        ));
    }

    public function getById(GetByIdRequest $request)
    {
        if ($request->user()->id == $request->id) return $this->success('Admin details', $request->user());

        $admin = $this->adminService->getById($request->id);

        return !$admin || ($admin->customer_id != $request->user()->customer_id)
            ? $this->error('Admin not found', 404)
            : $this->success('Admin details', $admin);
    }

    public function updateTheme(UpdateThemeRequest $request)
    {
        return $this->success('Theme updated successfully', $this->adminService->updateTheme($request->user()->id, $request->theme));
    }
}
