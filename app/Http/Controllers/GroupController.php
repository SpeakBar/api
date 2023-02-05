<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\User;
use Illuminate\Support\Arr;

class GroupController extends Controller
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
     * @param  \App\Http\Requests\StoreGroupRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreGroupRequest $request)
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
     * @param  \App\Models\Group  $group
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function show(Group $group)
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
     * @param  \App\Http\Requests\UpdateGroupRequest  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateGroupRequest $request, Group $group)
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
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
