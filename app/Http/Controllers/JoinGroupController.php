<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJoinRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class JoinGroupController extends Controller
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
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse|string[]
     */
    public function store(StoreJoinRequest $request, int $id)
    {
        $group = Group::find($id);
        if ($group == null) {
            return response()->json([
                'message' => "Not found.",
            ], 404);
        }

        if (! $group->users()->get()->contains($request->user())) {
            return response()->json([
                'message' => "Unauthorized."
            ], 401);
        }

        $user = User::find($request->user_id);
        if (! $group->users()->get()->contains($user)) {
            $group->users()->attach($user);
        }
        return [
            'message' => "Added",
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|string[]
     */
    public function destroy($id)
    {
        $group = Group::find($id);
        if ($group == null) {
            return response()->json([
                'message' => "Not found."
            ], 404);
        }

        if (auth()->id() == $group->owner_id) {
            $users = $group->users();
            $users->detach(auth()->user());
            $group->update([
                'owner_id' => $users->orderBy('joined_at', 'asc')->get()[0]->id
            ]);
            return [
                'message' => "Ok."
            ];
        }

        if (! $group->users()->get()->contains(auth()->user())) {
            return response()->json([
                'message' => "Unauthorized."
            ], 401);
        }
        $group->users()->detach(auth()->user());
        return [
            'message' => "Ok.",
        ];
    }
}
