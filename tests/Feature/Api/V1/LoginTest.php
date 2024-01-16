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
    public function testLoginWithCorrectCredentials()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/v1/login', [
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
    public function testLoginWithIncorrectCredentials()
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }
}
