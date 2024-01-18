<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\Mood;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Travel;

class TravelControllerTest extends TestCase
{

    public function test_travel_store()
    {
        // get a user with permission to create travels
        $permission = Permission::where('name', 'can_create_travels')->first();
        $user = User::whereIn('roleId', $permission->roles->pluck('id'))->first();

        // Create a fake travel request
        $travelData = Travel::factory()->make()->toArray();

        // Create a fake mood
        Mood::factory()->create(['name' => 'testHappiness']);
        $travelData['moods'] = [
            'testHappiness' => 5,
        ];

        // Send a POST request to the store endpoint with the travel data
        $response = $this->actingAs($user)->post(route('admin.travels.store'), $travelData);

        // Assert that the response has a 201 status code
        $response->assertStatus(201);

        // Assert that the response has the correct JSON structure
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'description',
                'numberOfDays',
                'numberOfNight',
                'moods'
            ],
        ]);

        // Assert that the travel record was created in the database
        $this->assertDatabaseHas('travels', [
            'name' => $travelData['name'],
            'slug' => $travelData['slug'],
        ]);

        $travel = Travel::where('slug', $travelData['slug'])->first();
        $this->assertEquals(5, $travel->moods->first()->pivot->value);
    }

    public function test_travel_update()
    {
        // get a user with permission to update travels
        $permission = Permission::where('name', 'can_update_travels')->first();
        $user = User::whereIn('roleId', $permission->roles->pluck('id'))->first();
        // Create a travel record
        $travel = Travel::factory()->create();

        // Create a fake travel request
        $updatedTravelData = Travel::factory()->make()->toArray();

        // Create a fake mood
        Mood::factory()->create(['name' => 'testHappiness']);
        $updatedTravelData['moods'] = [
            'testHappiness' => 7,
        ];

        // Send a PUT request to the update endpoint with the updated travel data
        $response = $this->actingAs($user)->put(route('admin.travels.update',[$travel]), $updatedTravelData);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

       // Assert that the response has the correct JSON structure
       $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'slug',
            'description',
            'numberOfDays',
            'numberOfNight',
            'moods'
        ],
    ]);

    // Assert that the travel record was created in the database
    $this->assertDatabaseHas('travels', [
        'name' => $updatedTravelData['name'],
        'slug' => $updatedTravelData['slug'],
    ]);

    $travel = Travel::where('slug', $updatedTravelData['slug'])->first();
    $this->assertEquals(7, $travel->moods->first()->pivot->value);
    }
}
