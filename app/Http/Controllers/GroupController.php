<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class GroupController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreGroupRequest $request
     * @return JsonResponse
     */
    public function store(StoreGroupRequest $request): JsonResponse
    {
        $request->user()->groups()->create([
            'name' => $request->name,
            'owner_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => "Created.",
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @return array|JsonResponse
     */
    public function show(Group $group): JsonResponse|array
    {
        $users = $group->users()->get();
        $data = [
            'name' => $group->name,
            'iconURL' => $group->iconURL,
            'created_at' => $group->created_at,
            'owner' => new UserResource(User::find($group->owner_id)),
            'members' => GroupResource::collection($users),
        ];

        if ($users->contains(key: 'id', value: auth()->id())) {
            return $data;
        }
        return response()->json([
            'message' => "Unauthorized."
        ], 401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateGroupRequest $request
     * @param Group $group
     * @return JsonResponse
     */
    public function update(UpdateGroupRequest $request, Group $group): JsonResponse
    {
        $group->update([
            'name' => $request->name,
        ]);
        return response()->json([
            'message' => "Updated."
        ], 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $group = Group::find($id);

        if ($group == null) {
            return response()->json([
                'message' => "Not Found."
            ], 404);
        }

        if ($group->owner_id == auth()->id()) {
            auth()->user()->groups()->delete();
            return response()->json([
                'message' => "Success"
            ]);
        }
        return response()->json([
            'message' => "Unauthorized."
        ], 401);
    }
}
