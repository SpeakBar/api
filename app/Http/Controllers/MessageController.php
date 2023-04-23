<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'sender_id' => $request->user()->id,
            'receiver_id' => $user->id,
            'content' => $request->get('content'),
            'reply' => $request->reply,
        ]);

        return response()->json($create, 201);
    }

    /**
     * Update message
     *
     * @param Request $request
     * @param User $user
     * @param Message $message
     * @return JsonResponse|Message
     */
    public function update(Request $request, User $user, Message $message): JsonResponse|Message
    {
        $valid = Validator::make($request->all(), [
            'content' => "max:512"
        ]);

        $update = $message->update($valid->valid());

        if ($update) {
            return $message;
        }
        return response()->json([
            'message' => "Unauthorized."
        ], 401);
    }

    /**
     * Delete a message
     *
     * @param Request $request
     * @param User $user
     * @param Message $message
     * @return JsonResponse
     */
    public function delete(Request $request, User $user, Message $message): JsonResponse
    {
        $author = $message->author()->first();

        if ($request->user()->id == $author->id)
        {
            return response()->json(['message' => "Success."]);
        }
        return response()->json(['message' => "Unauthorized."], 401);
    }

}
