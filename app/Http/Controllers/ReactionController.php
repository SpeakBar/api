<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SteppingHat\EmojiDetector\EmojiDetector;

class ReactionController extends Controller
{

    public function store(Request $request, User $user, Message $message): JsonResponse
    {
        $emoji = $request->get('emoji');
        $detector = new EmojiDetector();

        if (!$detector->isSingleEmoji($emoji)) {
            return response()->json([
                'message' => "Unauthorized."
            ], 401);
        }
        $message->reaction()->create([
            'emoji' => $emoji,
        ]);
        return response()->json([
            'message' => "Created."
        ], 201);
    }

    public function show(User $user, Message $message): array
    {
        return $message->reaction()->get()->jsonSerialize();
    }

    public function delete(User $user, Message $message, Reaction $reaction): JsonResponse
    {
        $deleted = $reaction->delete();

        if ($deleted) {
            return response()->json(['message' => "Success."]);
        }
        return response()->json(['message' => "Unauthorized."], 401);
    }

}
