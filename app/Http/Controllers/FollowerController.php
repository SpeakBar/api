<?php

namespace App\Http\Controllers;

use App\Http\Resources\FollowerResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class FollowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param User $user
     * @return string[]
     */
    public function store(Request $request, User $user): array
    {
        $exist = DB::table('followers')->where([
            'follower_id' => $request->user()->id,
            'following_id' => $user->id,
        ])->exists();

        if (! $exist) {
            $user->followers()->attach($request->user());
            return [
                'message' => "success"
            ];
        }
        return [
            'message' => "non"
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return response()->json(
            FollowerResource::collection($user->followers()->get())
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return JsonResponse|string[]
     */
    public function destroy(User $user)
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
