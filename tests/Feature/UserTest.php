<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_user()
    {
        $response = $this->post('/users', [
            "name" => fake()->name(),
            "email" => fake()->email(),
            "password" => fake()->password(),
        ], ['Authorization' => '123abc']);

        $response->assertStatus(201);
    }

    public function test_get_not_exist_user()
    {
        $response = $this->get('/users/4688465465', ['Authorization' => '123abc']);

        $response->assertStatus(404);
    }

    public function test_get_exist_user()
    {
        $response = $this->get('/users/1', ['Authorization' => '123abc']);

        $response->assertStatus(200);
    }
}
