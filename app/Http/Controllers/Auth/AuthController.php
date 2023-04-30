<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'=> ['required', 'email'],
            'password' => 'required',
            'remember' => 'boolean'
        ]);
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);
        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'message' => 'Email or password is incorrect'
            ], 422);
        }


        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        return response([
            'user' => new UserResource($user),
            'token' => $token
        ]);

    }

    public function signup(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email'=> ['required', 'email'],
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ])->assignRole('user');
        $token = $user->createToken('main')->plainTextToken;
        return response([
            'user' => new UserResource($user),
            'token' => $token,
        ]);

    }

    public function logout()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user){
            $user->currentAccessToken()->delete();
        }

        return response('', 204);
    }

    public function getUser(Request $request)
    {
        return new UserResource($request->user());
    }
}
