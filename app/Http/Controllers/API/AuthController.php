<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed',
        ]);

        if ( $validator->fails() ) {
            return response([
                'message' => $validator->errors()
            ], 401);
        }

        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([
            'success' => 'regitrasi user success',
            'data' => $user,
            'access_token' => $accessToken,
        ], 200);
    }

    public function login(Request $request)
    {
        $login = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if ( !auth()->attempt($login) ) {
            return response([
                'message' => 'user tidak ditemukan',
            ], 400);
        }

        $accessToken = $request->user()->createToken('authToken')->accessToken;

        return response([
            'success' => 'login success',
            'data' => auth()->user(),
            'access_token' => $accessToken,
        ], 200);
    }

    public function logout(Request $request)
    {
        if ( auth()->check() ) {
            $request->user()->token()->revoke();
        }
        return response([
            'success' => 'logout success',
        ], 200);
    }
}
