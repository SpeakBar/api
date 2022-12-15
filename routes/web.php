<?php

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
Route::get("/users/{id}", function () {
    return response()->json([
        "id" => '0',
        "username" => "test",
        "created_at" => "2014"
    ]);
})->where('id', '[0-9]+');