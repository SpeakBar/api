<?php

namespace App\Http\Controllers;

use App\Http\Resources\FollowerResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function follow(Request $request, User $user): JsonResponse
    {
        $exist = DB::table('followers')->where([
            'follower_id' => $request->user()->id,
            'following_id' => $user->id,
        ])->exists();

        if (! $exist) {
            $user->followers()->attach($request->user());
            return response()->json([
                'message' => "Created."
            ], 201);
        }
        return response()->json([
            'message' => "Accepted."
        ], 202);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = User::find($id);
        if ($user == null) {
            return response()->json([
                'message' => "Not Found."
            ], 404);
        }
        return response()->json(
            FollowerResource::collection($user->followers()->get())
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return JsonResponse|string[]
     */
    public function unfollow(User $user): array|JsonResponse
    {
        $exist = DB::table('followers')->where([
            'follower_id' => auth()->user()->id,
            'following_id' => $user->id,
        ])->exists();

        if ($exist) {
            $user->followers()->detach(auth()->user());
            return [
                'message' => "Success",
            ];
        }
        return response()->json([
            'message' => "Not Found."
        ], 404);
    }
}
