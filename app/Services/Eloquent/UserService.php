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
}
