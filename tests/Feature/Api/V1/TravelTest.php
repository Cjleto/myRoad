<?php

use App\Models\Mood;
use App\Models\Permission;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

test('user without permission cannot create travel', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['cannot_create_travels']
    );

    // Create a fake travel request
    $travelData = Travel::factory()->make()->toArray();

    // Create a fake mood
    Mood::factory()->create(['name' => 'testHappiness']);
    $travelData['moods'] = json_encode(['testHappiness' => 5]);

    // Send a POST request to the store endpoint with the travel data
    $response = $this->post(route('admin.travels.store'), $travelData);

    $response->assertStatus(403);
    $response->assertJson([
        'message' => 'You are not authorized to create travels',
    ]);
});

test('travel store', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['can_create_travels']
    );

    // Create a fake travel request
    $travelData = Travel::factory()->make()->toArray();

    // Create a fake mood
    Mood::factory()->create(['name' => 'testHappiness']);
    $travelData['moods'] = json_encode(['testHappiness' => 5]);

    // Send a POST request to the store endpoint with the travel data
    $response = $this->post(route('admin.travels.store'), $travelData);

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
            'moods',
        ],
    ]);

    // Assert that the travel record was created in the database
    $this->assertDatabaseHas('travels', [
        'name' => $travelData['name'],
        'slug' => $travelData['slug'],
    ]);

    $travel = Travel::where('slug', $travelData['slug'])->first();
    expect($travel->moods->first()->pivot->value)->toEqual(5);
});

test('travel update', function () {

    Sanctum::actingAs(
        User::factory()->create(),
        ['can_update_travels']
    );

    // Create a travel record
    $travel = Travel::factory()->create();

    // Create a fake travel request
    $updatedTravelData = Travel::factory()->make()->toArray();

    // Create a fake mood
    Mood::factory()->create(['name' => 'testHappiness']);
    $updatedTravelData['moods'] = json_encode(['testHappiness' => 7]);

    // Send a PUT request to the update endpoint with the updated travel data
    $response = $this->put(route('admin.travels.update', [$travel]), $updatedTravelData);

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
            'moods',
        ],
    ]);

    // Assert that the travel record was created in the database
    $this->assertDatabaseHas('travels', [
        'name' => $updatedTravelData['name'],
        'slug' => $updatedTravelData['slug'],
    ]);

    $travel = Travel::where('slug', $updatedTravelData['slug'])->first();
    expect($travel->moods->first()->pivot->value)->toEqual(7);
});

test('a photo can be added to a travel', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['can_create_travels']
    );

    // Create a fake travel request
    $travelData = Travel::factory()->make()->toArray();

    // Create a fake mood
    Mood::factory()->create(['name' => 'testHappiness']);
    $travelData['moods'] = json_encode(['testHappiness' => 5]);

    $response = $this->post(
        route('admin.travels.store', $travelData),
        [
            $travelData,
            'images' => [
                UploadedFile::fake()->create('123.jpg', 13),
                UploadedFile::fake()->create('456.png', 17),
            ],
        ],
    );
    $response->assertCreated();

    // get id from response json in data.id key
    $id = json_decode($response->getContent())->data->id;

    $travel = Travel::find($id);
    $media = $travel->getMedia('images');

    expect($media->count())->toEqual(2);

    // check for each media if it exists in storage
    foreach ($media as $image) {
        Storage::disk('public')->assertExists($image->id.'/'.$image->file_name);
        // delete image from storage
        Storage::disk('public')->delete($image->id.'/'.$image->file_name);
    }
});

