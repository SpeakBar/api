<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['api', 'auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/auth/account')->group(function () {
    Route::get('/', function () {
        return response()->json([
            "message" => "Unauthorized.",
        ], 401);
    })->name('login');
    Route::post('/', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/create', [\App\Http\Controllers\AuthController::class, 'store']);
    Route::delete('/delete', [\App\Http\Controllers\AuthController::class, 'delete'])->middleware(['auth:sanctum', 'api']);
});
