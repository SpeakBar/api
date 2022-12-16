<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return response()->json([
        "api" => "speakbar-api",
        "version" => "0.0.1v"
    ]);
});

// Users
Route::post("/users", function (Request $request) {
    $has = $request->has(['name', 'email', 'password']);
    if ($has) {
        DB::table("users")->insert([
            "name" => $request->post('name'),
            "email" => $request->post('email'),
            "password" => $request->post('password'),
            "created_at" => now(),
            "updated_at" => now(),
        ]);
        return response()->json([
            "success" => true,
            "message" => "User created successfully!"
        ]);
    }
    return response(status:401)->json([
        "success" => false,
        "message" => "Your field has not good"
    ]);
});

Route::get("/users/{id}", function (int $id) {
    $user = DB::table("users")->where('id', '=', intval($id))->get(['id', 'name', 'email', 'created_at', 'updated_at']);

    if ($user->isEmpty()) {
        return response()->json([
            "error" => 404,
            "message" => "User not found."
        ], 404);
    }
    return response()->json($user->toArray());
})->where('id', '[0-9]+');