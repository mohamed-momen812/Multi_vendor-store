<?php

namespace App\Actions\Fortify;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthenticateUser
{
    /**
     * login with username, email or phone_number only for admins
     * @param mixed $request
     * @return bool|
     */
    public function authenticate($request)
    {
        $username = $request->post(config('fortify.username'));
        $password = $request->post('password');

        $user = Admin::where('username', $username)
            ->orwhere('email', $username)
            ->orwhere('phone_number', $username)
            ->first();

        if($user && Hash::check($password, $user->password)) {
            return $user;
        }

        return false;

    }
}
