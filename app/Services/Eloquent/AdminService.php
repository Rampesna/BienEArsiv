<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Admin;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AdminService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Admin);
    }

    /**
     * @param int $pageIndex
     * @param int $pageSize
     */
    public function index(
        int $pageIndex = 1,
        int $pageSize = 5
    )
    {
        $admins = Admin::orderBy('id', 'desc');

        return [
            'totalCount' => $admins->count(),
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize,
            'admins' => $admins->skip($pageSize * $pageIndex)
                ->take($pageSize)
                ->get()
        ];
    }

    /**
     * @param string $email
     * @param string $password
     */
    public function login(
        $email,
        $password
    )
    {
        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            return 404;
        }

        if (!Hash::check($password, $admin->password)) {
            return 401;
        }

        $token = $admin->createToken('adminApiToken');

        $admin->api_token = $token->plainTextToken;
        $admin->save();

        return [
            'token' => $token->plainTextToken,
            'oauth' => Crypt::encrypt($admin->id)
        ];
    }

    /**
     * @param string $email
     */
    public function getByEmail(
        $email
    )
    {
        return Admin::where('email', $email)->first();
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function create(
        string $name,
        string $email,
        string $password
    )
    {
        $admin = new Admin;
        $admin->name = $name;
        $admin->email = $email;
        $admin->password = Hash::make($password);
        $admin->save();

        return $admin;
    }

    /**
     * @param int $id
     * @param int $theme
     */
    public function updateTheme(
        $id,
        $theme
    )
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return 404;
        }

        $admin->theme = $theme;
        $admin->save();

        return $admin;
    }

    /**
     * @param Admin $admin
     * @param string $password
     */
    public function updatePassword(
        Admin   $admin,
        string $password
    )
    {
        $admin->password = $password;
        $admin->save();

        return $admin;
    }
}
