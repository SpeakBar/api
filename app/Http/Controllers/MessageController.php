<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Channel;
use App\Models\Message;
use App\Models\User;
use DB;
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
        $content = $request->get('content');

        $exist = DB::table('channel_user')->where([
            'user_id' => $user->id,
        ])->where([
            'user_id' => $request->user()->id,
        ])->exists();

        if (!$exist) {
            $channel = Channel::create();
            $channel->users()->attach($request->user());
            $channel->users()->attach($user);
        }

        if ($user->is($request->user())) {
            return response()->json([
                'message' => "Unauthorized."
            ], 401);
        }
        if ($request->get('encrypted')) {
            $content = openssl_encrypt($content, "aes-128-gcm", $request->get("key"), 0, $request->get("key"), $tag);
            $encrypt_key = $tag;
        } else {
            $encrypt_key = null;
        }
        $create = Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $user->id,
            'content' => $content,
            'reply' => $request->get('reply'),
            'encrypted' => $request->get('encrypted') ?? false,
            'encrypt_key' => $encrypt_key,
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

    /**
     * Decrypt message
     *
     * @param Request $request
     * @param User $user
     * @param Message $message
     * @return JsonResponse
     */
    public function decrypt(Request $request, User $user, Message $message): JsonResponse
    {
        if (!$message->encrypted)
            return response()->json(['message' => "Unauthorized."], 401);

        $validator = Validator::make($request->all(), [
            'key' => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => "Unauthorized."], 401);
        }

        $content = openssl_decrypt(
            $message->content,
            "aes-128-gcm",
            $validator->safe()['key'],
            0,
            $validator->safe()['key'],
            $message->encrypt_key
        );

        if (!$content) {
            return response()->json([
                'message' => "Error."
            ]);
        }
        return response()->json([
            'message' => "Success.",
            'content' => $content,
        ]);
    }
}
