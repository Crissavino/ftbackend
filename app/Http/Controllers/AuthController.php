<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([
            'success' => true,
            'user' => $user,
            'token' => $accessToken
        ], 201);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response([
                'success' => false,
                'message' => 'This User does not exist, check your details'
            ], 400);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response([
            'success' => true,
            'user' => auth()->user(),
            'token' => $accessToken
        ]);
    }

    public function logout(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->AuthAccessToken()->delete();

        return response([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }
}
