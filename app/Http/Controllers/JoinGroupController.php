<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJoinRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JoinGroupController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreJoinRequest $request
     * @param Group $group
     * @return JsonResponse|string[]
     */
    public function join(StoreJoinRequest $request, Group $group): array|JsonResponse
    {
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  Group  $group
     * @return JsonResponse|string[]
     */
    public function kick(Request $request, Group $group): array|JsonResponse
    {
        if ($group->owner_id != $request->user()->id) {
            return response()->json([
                'message' => "Unauthorized.",
            ], 401);
        }

        if (! $group->users()->get()->contains(User::find($request->user_id))) {
            return response()->json([
                'message' => "Invalid body.",
            ], 401);
        }

        $group->users()->detach($request->user_id);
        return [
            'message' => "Ok.",
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Group $group
     * @return JsonResponse|string[]
     */
    public function leave(Group $group): array|JsonResponse
    {
        if (auth()->id() == $group->owner_id) {
            $users = $group->users();
            $users->detach(auth()->user());
            $group->update([
                'owner_id' => $users->orderBy('joined_at')->get()[0]->id
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
