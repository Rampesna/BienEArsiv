<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new User);
    }

    /**
     * @param int $customerId
     * @param int $pageIndex
     * @param int $pageSize
     */
    public function index(
        int $customerId,
        int $pageIndex = 1,
        int $pageSize = 5
    )
    {
        $users = User::where('customer_id', $customerId)->orderBy('id', 'desc');

        return [
            'totalCount' => $users->count(),
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize,
            'users' => $users->skip($pageSize * $pageIndex)
                ->take($pageSize)
                ->get()
        ];
    }

    /**
     * @param int $customerId
     */
    public function count(
        int $customerId,
    )
    {
        return User::where('customer_id', $customerId)->count();
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
        $user = User::where('email', $email)->first();

        if (!$user) {
            return 404;
        }

        if (!Hash::check($password, $user->password)) {
            return 401;
        }

        $token = $user->createToken('apiToken');

        $user->api_token = $token->plainTextToken;
        $user->save();

        return [
            'token' => $token->plainTextToken,
            'oauth' => Crypt::encrypt($user->id)
        ];
    }

    /**
     * @param string $email
     */
    public function getByEmail(
        $email
    )
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param int $customerId
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $password
     */
    public function create(
        int    $customerId,
        string $name,
        string $surname,
        string $email,
        string $password
    )
    {
        $user = new User;
        $user->customer_id = $customerId;
        $user->name = $name;
        $user->surname = $surname;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $surname
     * @param string $phone
     */
    public function update(
        int    $id,
        string $name,
        string $surname,
        string $phone
    )
    {
        $user = User::find($id);
        $user->name = $name;
        $user->surname = $surname;
        $user->phone = $phone;
        $user->save();

        return $user;
    }

    /**
     * @param int $id
     */
    public function delete(
        int $id
    )
    {
        return User::find($id)->delete();
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
        $user = User::find($id);

        if (!$user) {
            return 404;
        }

        $user->theme = $theme;
        $user->save();

        return $user;
    }

    /**
     * @param User $user
     * @param string $password
     */
    public function updatePassword(
        User   $user,
        string $password
    )
    {
        $user->password = $password;
        $user->save();

        return $user;
    }
}
