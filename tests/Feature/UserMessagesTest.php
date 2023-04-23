<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserMessagesTest extends TestCase
{

    private string $uri;

    private Authenticatable|HasApiTokens $sender;

    private User $receiver;

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
            'sender_id' => $this->sender->id,
            'receiver_id' => $this->receiver->id,
            'content' => fake()->sentence,
        ]);

        $response = $this->post($this->uri, [
            'content' => fake()->sentence(),
            'reply' => $message->id,
        ]);

        $response->assertStatus(201);
    }

    /**
     * Test update message
     *
     * @return void
     */
    public function test_update_message(): void
    {
        $message = Message::create([
            'content' => 'Je suis une chips',
            'sender_id' => $this->sender->id,
            'receiver_id' => $this->receiver->id,
        ]);

        $response = $this->put($this->uri . '/' . $message->id, [
            'content' => "Doe"
        ]);

        $this->assertEquals("Doe", $response->json('content'));

        $response->assertJson(['content' => "Doe"]);
    }

    /**
     * Test delete message
     */
    public function test_delete_message() {
        $message = $this->post($this->uri, [
            'content' => "John Doe.",
        ]);

        $id = $message->json('id');
        $response = $this->delete($this->uri . "/" . $id);
        $response->assertStatus(200);
    }
}
