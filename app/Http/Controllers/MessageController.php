<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ParagonIE\Halite\Alerts\CannotPerformOperation;
use ParagonIE\Halite\Alerts\InvalidDigestLength;
use ParagonIE\Halite\Alerts\InvalidKey;
use ParagonIE\Halite\Alerts\InvalidMessage;
use ParagonIE\Halite\Alerts\InvalidSignature;
use ParagonIE\Halite\Alerts\InvalidType;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\Crypto;
use ParagonIE\HiddenString\HiddenString;

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
        $encrypt_key = null;

        if ($user->is($request->user())) {
            return response()->json([
                'message' => "Unauthorized."
            ], 401);
        }

        if ($request->encrypted) {
            try {
                $encrypt_key = KeyFactory::generateEncryptionKey();

                $content = Crypto::encryptWithAD(
                    new HiddenString($request->get('content')),
                    $encrypt_key,
                    $request->key
                );
            } catch (CannotPerformOperation|InvalidKey $e) {
                $request->encrypted = false;
            }
        } else {
            $content = $request->get('content');
        }

        $create = Message::create([
            'channel' => min($user->id, $request->user()->id) . "-" . max($user->id, $request->user()->id),
            'user_id' => $request->user()->id,
            'content' => $content,
            'encrypted' => $request->encrypted,
            'encrypt_key' => $encrypt_key,
            'reply' => $request->reply,
        ]);

        return response()->json($create, 201);
    }

    /**
     * Decrypt a message if is encrypted
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function decrypt(Request $request, int $id): JsonResponse
    {
        $message = Message::find($id);

        if ($message == null) {
            return response()->json([
                'message' => "Not found.",
            ], 404);
        }

        if (! $message->encrypted) {
            return response()->json([
                'message' => "Unauthorized."
            ], 401);
        }

        try {
            $content = Crypto::decryptWithAD($message->content, $message->encrypte_key, $request->key)->getString();

            return response()->json([
                'content' => $content,
            ]);
        } catch (CannotPerformOperation|InvalidDigestLength|InvalidMessage|InvalidSignature|InvalidType|\SodiumException $e) {
            return response()->json([
                'message' => "Unauthorized.",
            ], 401);
        }
    }

}
