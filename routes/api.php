<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\JoinGroupController;
use App\Http\Controllers\PrivateMessageController;
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
    Route::post('/', [AuthController::class, 'login']);
    Route::post('/create', [AuthController::class, 'store']);
    Route::delete('/delete', [AuthController::class, 'delete'])->middleware(['auth:sanctum', 'api']);
});

Route::middleware(['api', 'auth:sanctum'])->group(function () {
   Route::prefix("/users/{user}/dm")->group(function () {
       Route::post('/', [PrivateMessageController::class, 'store']);
       Route::get('/', [PrivateMessageController::class, 'index']);
   });

   Route::prefix("/users/{user}/follow")->group(function () {
       Route::post('/', [FollowerController::class, 'store']);
       Route::get('/', [FollowerController::class, 'show']);
       Route::delete('/', [FollowerController::class, 'destroy']);
   });
   Route::prefix("/groups")->group(function () {
       Route::post('/', [GroupController::class, 'store']);
       Route::get('/{group}', [GroupController::class, 'show']);
       Route::delete('/{group}', [GroupController::class, 'destroy']);
       Route::put('/{group}', [GroupController::class, 'update']);

       Route::post('/{group}/add', [JoinGroupController::class, 'store']);
       Route::delete('/{group}/leave', [JoinGroupController::class, 'destroy']);
       Route::put('/{group}/kick', [JoinGroupController::class, 'update']);
   });
});
