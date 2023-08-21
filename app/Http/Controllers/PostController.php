<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function store(Request $request, User $user) {
        if (!$user->is(auth()->user())) {
            return response()->json([
                'message' => "Unauthorized."
            ], 401);
        }
        Post::create([
           'content' => $request->get('content'),
            'user_id' => $user->id,
        ]);
        return response()->json([
            'message' => "Created.",
        ], 201);
    }

    public function show(Request $request) {

    }

}
