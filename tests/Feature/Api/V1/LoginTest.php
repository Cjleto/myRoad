<?php

use App\Models\User;

describe('login', function () {
    test('login with correct credentials', function () {
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
    });

    test('login with incorrect credentials', function () {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);

    });

});
