<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * Test login with correct credentials.
     *
     * @return void
     */
    public function test_login_with_correct_credentials()
    {
        $user = User::factory()->create(['roleId' => 1]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'token',
            ],
        ]);

    }

    /**
     * Test login with incorrect credentials.
     *
     * @return void
     */
    public function test_login_with_incorrect_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }
}
