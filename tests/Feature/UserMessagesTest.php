<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserMessagesTest extends TestCase
{

    private string $uri;

    private Authenticatable|HasApiTokens $sender;

    private Collection|Model $receiver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->receiver = User::factory()->create();

        $this->uri = "/api/users/" . $this->receiver->id . "/messages";
        $this->sender = Sanctum::actingAs(
            User::factory()->create()
        );
    }

    /**
     * Test user send message
     *
     * @return void
     */
    public function test_create_message(): void
    {
        $response = $this->post($this->uri, [
            'content' => fake()->sentence(10),
        ]);

        $response->assertStatus(201);
    }

    /**
     * Test reply sender message
     *
     * @return void
     */
    public function test_reply_sender_message(): void
    {
        $message = Message::create([
            'channel' => min($this->sender->id, $this->receiver->id) . '-' . max($this->sender->id, $this->receiver->id),
            'content' => fake()->sentence,
            'user_id' => $this->sender->id
        ]);

        $response = $this->post($this->uri, [
            'content' => fake()->sentence(),
            'reply' => $message->id,
        ]);

        $response->assertStatus(201);
    }
}
