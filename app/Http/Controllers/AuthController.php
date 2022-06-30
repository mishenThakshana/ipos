<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required | string | unique:users,username',
            'email'    => 'required | string | unique:users,email',
            'password' => 'required | string | confirmed',
        ]);

        $user = User::create([
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'type' => 'admin',
        ]);

        $token = $user->createToken('userAccessToken')->plainTextToken;

        $response = [
            'userId' => $user->id,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required | string ',
            'password' => 'required | string'
        ]);

        //Check email
        $user = User::where('email', $request->email)->first();

        //Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            $response = [
                'errorMessage' => 'Incorrect Credentials!'
            ];

            return response($response);
        }

        $token = $user->createToken('myAppToken')->plainTextToken;

        $response = [
            'userId' => $user->id,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'logged out'
        ];
    }
}
