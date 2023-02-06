<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoutesTest extends TestCase
{
    use RefreshDatabase;

    const ROUTE_REGISTER = '/api/auth/account/register';
    const ROUTE_LOGIN = '/api/auth/account/login';

    /**
     * Test register a user
     *
     * @return void
     */
    public function test_sucess_register_user()
    {
        $response = $this->post($this::ROUTE_REGISTER, [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test route register with error
     *
     * @return void
     */
    public function test_password_unauthorized_register_user()
    {
        $response = $this->post($this::ROUTE_REGISTER, [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'password',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => "Unauthorized.",
        ]);
    }

    /**
     * Test route register with error
     *
     * @return void
     */
    public function test_email_unauthorized_register_user()
    {
        $response = $this->post($this::ROUTE_REGISTER, [
            'name' => fake()->name(),
            'email' => 'test',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => "Unauthorized.",
        ]);
    }

    /**
     * Test route register with error
     *
     * @return void
     */
    public function test_name_unauthorized_register_user()
    {
        $response = $this->post($this::ROUTE_REGISTER, [
            'name' => fake()->sentence(25),
            'email' => fake()->email(),
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => "Unauthorized.",
        ]);
    }

    /**
     * Test route login
     *
     * @return void
     */
    public function test_success_login()
    {
        $user = User::factory(1)->create()->first();

        $response = $this->post($this::ROUTE_LOGIN, [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }
}
