<?php

namespace Tests\Feature\Api\V1;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Tests\TestCase;

class TravelControllerTest extends TestCase
{
    public function testTravelIndex()
    {
        $user = User::factory()->create(['roleId' => Role::where('name', 'admin')->first()->id]);

        // Create some travel records
        $travels = Travel::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/api/v1/travels');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'moods',
                ],
            ],
        ]);
    }

    public function testTravelStore()
    {
        $user = User::factory()->create(['roleId' => Role::where('name', 'admin')->first()->id]);

        // Create a fake travel request
        $travelData = Travel::factory()->make()->toArray();

        // Send a POST request to the store endpoint with the travel data
        $response = $this->post('/api/v1/travels', $travelData);

        // Assert that the response has a 201 status code
        $response->assertStatus(201);

        // Assert that the response has the correct JSON structure
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                // Add other expected attributes here
            ],
        ]);

        // Assert that the travel record was created in the database
        $this->assertDatabaseHas('travels', $travelData);
    }

    public function testShow()
    {
        // Create a travel record
        $travel = Travel::factory()->create();

        // Send a GET request to the show endpoint with the travel ID
        $response = $this->get('/api/v1/travels/'.$travel->id);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Assert that the response has the correct JSON structure
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                // Add other expected attributes here
            ],
        ]);
    }

    public function testUpdate()
    {
        // Create a travel record
        $travel = Travel::factory()->create();

        // Create a fake travel request
        $updatedTravelData = Travel::factory()->make()->toArray();

        // Send a PUT request to the update endpoint with the updated travel data
        $response = $this->put('/api/v1/travels/'.$travel->id, $updatedTravelData);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Assert that the response has the correct JSON structure
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                // Add other expected attributes here
            ],
        ]);

        // Assert that the travel record was updated in the database
        $this->assertDatabaseHas('travels', $updatedTravelData);
    }
}
