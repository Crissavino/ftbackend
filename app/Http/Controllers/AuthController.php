<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


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
        $validatedData['online'] = 1;

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        Token::create([
            'token' => $accessToken,
            'user_id' => $user->id
        ]);

        $user->positions;
        $user->location;
        $user->daysAvailables;

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

        Token::create([
            'token' => $accessToken,
            'user_id' => auth()->user()->id
        ]);

        $user = auth()->user();
        $user->positions;
        $user->location;
        $user->daysAvailables;

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

        $headerToken = explode(' ', $request->header('authorization'))[1];
        $token = $user->tokens()->where('token', $headerToken)->first();
        $token->delete();

        return response([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function existEmail(Request $request)
    {

        $existEmail = User::where('email', $request->email)->exists();

        if ($existEmail) {
            return response([
                'success' => true,
                'message' => 'This email already exists'
            ]);
        }

        return response([
            'success' => false,
        ]);
    }
}
