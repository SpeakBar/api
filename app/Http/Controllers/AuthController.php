<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param StoreUserRequest $request
     * @return array
     */
    public function register(StoreUserRequest $request): array
    {
        $created = User::create([
            'uuid' => Str::uuid(),
            'name' => $request->name,
            'banner' => "#f6b93b",
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return [
            'account' => $created,
        ];
    }

    /**
     * Login
     *
     * @param LoginUserRequest $request
     * @return array|JsonResponse
     */
    public function login(LoginUserRequest $request): JsonResponse|array
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->all())) {
            return response()->json([
                'code' => 401,
                'message' => "non non non",
            ]);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('api')->plainTextToken;

        return [
            'code' => 200,
            'message' => "ok ok ok",
            'token' => $token,
        ];
    }

    /**
     * Get user profile
     *
     * @param Request $request
     * @return UserResource
     */
    public function profile(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    /**
     * Delete a account
     *
     * @param Request $request
     * @return array
     */
    public function delete(Request $request): array
    {
        $delete = $request->user()->delete();

        return [
            'deleted' => $delete,
        ];
    }
}
