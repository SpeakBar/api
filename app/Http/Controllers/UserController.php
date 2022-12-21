<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    
    /**
     * Display user information by id
     * 
     * @param int $id
     * @return void
     */
    public function show(int $id)
    {
        $user = DB::table("users")->where('id', '=', intval($id))->get(['id', 'name', 'email', 'created_at', 'updated_at']);

        if ($user->isEmpty()) {
            return response()->json([
                "error" => 404,
                "message" => "User not found."
            ], 404);
        }
        return response()->json($user->toArray());
    }

    /**
     * Create user account
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $dataResponse = [
            "created" => true,
            "message" => "The account has been created"
        ];

        if ($request->has(['name', 'email', 'password'])) {
            $insert = DB::table("users")->insert([
                "name" => $request->post('name'),
                "email" => $request->post('email'),
                "password" => $request->post('password'),
                "created_at" => now(),
                "updated_at" => now(),
            ]);
            if ($insert == false) {
                $dataResponse = [
                    "created" => false,
                    "message" => "An error occurred while creating the account"
                ];
            }
        } else {
            $dataResponse = [
                "created" => false,
                "message" => "You did not send the right files"
            ];
        }
        if ($dataResponse["created"] == false) {
            return response()->json($dataResponse, 500);
        }
        return response()->json($dataResponse, 201);
    }

}
