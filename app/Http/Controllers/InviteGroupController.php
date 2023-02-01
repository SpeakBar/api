<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InviteGroupController extends Controller
{
    public function store(Request $request, Group $group) {
        $group = $request->user()->groups()->find($group->id);

        if ($group == null) {
            return response()->json([
                'message' => "Not found."
            ], 404);
        }
        return $group->invites()->create([
            'code' => Str::random(),
            'user_id' => $request->user()->id,
        ]);
    }
}
