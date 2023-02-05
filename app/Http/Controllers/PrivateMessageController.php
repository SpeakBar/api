<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrivateMessageRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PrivateMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param User $user
     * @return Collection
     */
    public function index(Request $request, User $user): Collection
    {
        // TODO : Condition is false.
        return DB::table('private_messages')
            ->where([
                'channel_id' => $user->id,
                'user_id' => $request->user()->id,
            ])
            ->orWhere([
                'channel_id' => $request->user()->id,
                'user_id' => $user->id,
            ])
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePrivateMessageRequest $request
     * @param User $user
     * @return array
     */
    public function store(StorePrivateMessageRequest $request, User $user): array
    {
        $message = $request->user()->dms()->create([
            'content' => $request->all('content')['content'],
            'channel_id' => $user->id,
        ]);
        return ['message' => $message];
    }
}
