<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'device_name' => 'string|max:255'
        ]);

        $user = User::where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password)) {

            $device_name = $request->post('device_name', $request->userAgent());

            // createToken function in hasApiToken traite take as a parameter name of the created token
            $token = $user->createToken($device_name);

            return Response::json([
                'token' => $token->plainTextToken,
                'user' => $user
            ], 201);
        }

        return Response::json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function destroy($token = null) {

        $user = Auth::guard('sanctum')->user();

        if($token === null) {
            $user->currentAccessToken()->delete();
            return;
        }

        // Revoke all tokens, logout from all devices
        // $user->tokens()->delete();

        // form PersonalAccessToken::findToken() method in sanctum Find the token instance matching the given token after hashing.
        $personalAccessToken = PersonalAccessToken::findToken($token);

        if( // ensure that the auth user is the user in the token
            $user->id == $personalAccessToken->tokenable_id
            && get_class($user) == $personalAccessToken->tokenable_type
        ){
            $personalAccessToken->delete();
        }

        // access to the tokens realation from trait HasApiTokens and compair the given token to the token in db
        // but the token in db is hashed so can't be ture
        // $user->tokens()->where('token', $token)->delete();

    }
}
