<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UserController\GetAllRequest;
use App\Http\Requests\Api\Admin\UserController\GetByIdRequest;
use App\Http\Requests\Api\Admin\UserController\UpdateRequest;
use App\Services\Eloquent\UserService;
use App\Traits\Response;
class UserController extends Controller
{
    use Response;

    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }


    public function getAll( GetAllRequest $request)
    {
        return $this->success('Users', $this->userService->getAll());
    }

    public function getById(GetByIdRequest $request)
    {
        return $this->success('User', $this->userService->getById($request->id));
    }

    public function update(UpdateRequest $request)
    {
        return $this->success('Güncelleme Başarılı', $this->userService->updateUser(
            $request->id,
            $request->name,
            $request->surname,
            $request->email,
            $request->title,
            $request->phone,
            $request->suspend,
        ));
    }

}
