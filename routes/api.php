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

Route::middleware(['api', 'auth:sanctum'])->group(function () {
   Route::prefix("/users/{user}/dm")->group(function () {
       Route::post('/', [\App\Http\Controllers\PrivateMessageController::class, 'store']);
       Route::get('/', [\App\Http\Controllers\PrivateMessageController::class, 'index']);
   });

   Route::prefix("/users/{user}/follow")->group(function () {
        Route::post('/', [\App\Http\Controllers\FollowerController::class, 'store']);
        Route::get('/', [\App\Http\Controllers\FollowerController::class, 'show']);
        Route::delete('/', [\App\Http\Controllers\FollowerController::class, 'destroy']);
   });

   Route::prefix("/groups")->group(function () {
      Route::post('/', [\App\Http\Controllers\GroupController::class, 'store']);
      Route::addRoute(['PUT', 'PATCH'], '/{group}', [\App\Http\Controllers\GroupController::class, 'update'])->where('group', '[0-9]+');
   });
});
