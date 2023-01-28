<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param StoreUserRequest $request
     */
    public function store(StoreUserRequest $request) {
        $created = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);

        return [
            'account' => $created,
        ];
    }

    /**
     * Login
     *
     * @param LoginUserRequest $request
     */
    public function login(LoginUserRequest $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (! \Illuminate\Support\Facades\Auth::attempt($request->all())) {
            return response()->json([
                'code' => 401,
                'message' => "non non non",
            ]);
        }

        $user = \Illuminate\Support\Facades\Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('api')->plainTextToken;

        return [
            'code' => 200,
            'message' => "ok ok ok",
            'token' => $token,
        ];
    }

    /**
     * Delete a account
     *
     * @param Request $request
     */
    public function delete(Request $request) {
        $delete = $request->user()->delete();

        return [
            'deleted' => $delete,
        ];
    }
}
