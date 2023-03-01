<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    /**
     * Create a new message
     *
     * @param StoreMessageRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request, User $user): JsonResponse
    {
        if ($user->is($request->user())) {
            return response()->json([
                'message' => "Unauthorized."
            ], 401);
        }

        $create = Message::create([
            'channel' => min($user->id, $request->user()->id) . "-" . max($user->id, $request->user()->id),
            'user_id' => $request->user()->id,
            'content' => $request->get('content'),
            'reply' => $request->reply,
        ]);

        return response()->json($create, 201);
    }

}
