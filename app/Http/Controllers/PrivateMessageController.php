<?php

namespace App\Http\Controllers;

use App\Models\PrivateMessage;
use App\Http\Requests\StorePrivateMessageRequest;
use App\Http\Requests\UpdatePrivateMessageRequest;
use App\Models\User;
use Illuminate\Http\Request;

class PrivateMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        return PrivateMessage::where([
            'channel_id' => $user->id,
            'user_id' => $request->user()->id
        ])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePrivateMessageRequest  $request
     * @param User $user
     * @return array
     */
    public function store(StorePrivateMessageRequest $request, User $user)
    {
        $message = $request->user()->dms()->create([
            'content' => $request->all('content')['content'],
            'channel_id' => $user->id,
        ]);
        return ['message' => $message];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PrivateMessage  $privateMessage
     * @return \Illuminate\Http\Response
     */
    public function show(PrivateMessage $privateMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePrivateMessageRequest  $request
     * @param  \App\Models\PrivateMessage  $privateMessage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrivateMessageRequest $request, PrivateMessage $privateMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PrivateMessage  $privateMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrivateMessage $privateMessage)
    {
        //
    }
}