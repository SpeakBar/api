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

    /**
     * Test not valid request encrypt/decrypt message
     */
    public function test_not_valid_request_encrypt_decrypt_message() {
        $response = $this->post($this->uri, [
            'content' => "John Doe.",
            'encrypted' => true,
            'key' => "42",
        ]);
        $response->assertStatus(201);
        $response->assertJson(['encrypted' => true]);
        $id = $response->json('id');

        $response = $this->post($this->uri, [
            'content' => "John Doe.",
            'encrypted' => true,
        ]);
        $response->assertStatus(401);

        $response = $this->get($this->uri . "/" . $id . "/decrypt");
        $response->assertStatus(401);
    }

    /**
     * Test valid request encrypt/decrypt message
     */
    public function test_valid_request_encrypt_decrypt_message() {
        $response = $this->post($this->uri, [
            'content' => "John Doe.",
            'encrypted' => true,
            'key' => "42",
        ]);
        $response->assertStatus(201);

        $response = $this->get($this->uri . "/" . $response->json('id') . "/decrypt?key=42");

        $response->assertJson(['message' => "Success."]);
        $this->assertEquals("John Doe.", $response->json('content'));
    }
}
