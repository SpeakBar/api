<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
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

    /**
     * Test get user profile
     *
     * @return void
     */
    public function test_user_profile()
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user
        );

        $response = $this->get('/api/profile');

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $user->id,
            'avatar' => $user->avatar,
            'name' => $user->name,
            'biography' => $user->biography,
            'daily_status' => $user->daily_status,
            'banner' => [
                'type' => ! URL::isValidUrl($user->banner) ? "URL" : "COLOR",
                'content' => $user->banner,
            ],
            'created_at' => $user->created_at->toString(),
        ], true);
    }
}
