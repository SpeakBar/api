<?php

use App\Http\Controllers\UserController;
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

Route::middleware('role:admin')->group(function () {
    // Users
    Route::get('/users/{id}', [UserController::class, "show"])->where('id', "[0-9]+");
    Route::post('/users', [UserController::class, "store"]);
});