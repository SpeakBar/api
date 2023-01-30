<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFollowerRequest;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;

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
    public function store(Request $request, User $user)
    {
        $exist = Follower::where([
            'follower_id' => $request->user()->id,
            'following_id' => $user->id,
        ])->exists();

        if (! $exist) {
            return Follower::create([
                'follower_id' => $request->user()->id,
                'following_id' => $user->id,
            ]);
        }
        return [
            'message' => "Deja follow"
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function show(User $user)
    {
        return $user->followers()->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @param  Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, User $user)
    {
        $follower = Follower::where([
            'following_id' => $user->id,
            'follower_id' => $request->user()->id,
        ]);

        if (! $follower->exists()) {
            return [
                'delete' => $follower->delete()
            ];
        }
        return response()->json([
            'message' => "Not found."
        ], 404);
    }
}
