<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\JoinGroupController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PrivateMessageController;
use App\Http\Controllers\ReactionController;
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
    Route::get('/login', function () {
        return response()->json([
            "message" => "Unauthorized.",
        ], 401);
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::delete('/delete', [AuthController::class, 'delete'])->middleware(['auth:sanctum', 'api']);
});

Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::get("/profile", [AuthController::class, 'profile']);

    Route::prefix("/users/{user}")->group(function () {
        Route::get('/follow', [FollowerController::class, 'show']);
        Route::post('/follow', [FollowerController::class, 'follow']);
        Route::post('/unfollow', [FollowerController::class, 'unfollow']);

        Route::prefix("/messages")->group(function () {
            Route::post("/", [MessageController::class, 'store']);
            Route::put("/{message}", [MessageController::class, 'update']);
            Route::delete("/{message}", [MessageController::class, 'delete']);
            Route::get("/{message}/decrypt", [MessageController::class, 'decrypt']);

            Route::post("/{message}/react", [ReactionController::class, 'store']);
            Route::get("/{message}/react", [ReactionController::class, 'show']);
            Route::delete("/{message}/react/{reaction}", [ReactionController::class, 'delete']);
        });

        Route::prefix("/posts")->group(function () {
            Route::post("/", [PostController::class, 'store']);
            Route::get("", [PostController::class, 'show']);
        });
    });

   Route::prefix("/groups")->group(function () {
       Route::post('/', [GroupController::class, 'store']);

       Route::prefix('/{group}')->group(function () {
           Route::get('/', [GroupController::class, 'show']);
           Route::delete('/', [GroupController::class, 'destroy']);
           Route::put('/', [GroupController::class, 'update']);

           Route::post('/add', [JoinGroupController::class, 'join']);
           Route::post('/leave', [JoinGroupController::class, 'leave']);
           Route::put('/kick', [JoinGroupController::class, 'kick']);
       });
   });
});
