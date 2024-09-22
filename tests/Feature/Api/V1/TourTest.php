<?php

use App\Enums\TravelVisibilityEnum;
use App\Exceptions\Travel\TravelIsNotPublicException;
use App\Models\Tour;
use App\Models\Travel;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;

test('store tour', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['can_create_tours']
    );

    $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);

    $startingDate = '2024-11-05';
    $endingDate = Carbon::parse($startingDate)->addDays($travel->numberOfDays)->format('Y-m-d');

    $data = [
        'travelId' => $travel->id,
        'startingDate' => $startingDate,
        'endingDate' => $endingDate,
        'price' => 300,
    ];

    $response = $this->post(route('admin.tours.store'), $data);

    //dd($response);
    $response->assertCreated();
    $response->assertJsonFragment([
        'travelId' => $travel->id,
        'price' => 300,
        'startingDate' => '2024-11-05',
        'endingDate' => $endingDate,
    ]);

    $this->assertDatabaseHas('tours', [
        'travelId' => $travel->id,
        'startingDate' => $startingDate,
        'endingDate' => $endingDate,
        'price' => 300 * 100,
    ]);
});

test('user without permission cannot create tour', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['cannot_create_tours']
    );

    $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);

    $startingDate = '2024-11-05';
    $endingDate = Carbon::parse($startingDate)->addDays($travel->numberOfDays)->format('Y-m-d');

    $data = [
        'travelId' => $travel->id,
        'startingDate' => $startingDate,
        'endingDate' => $endingDate,
        'price' => 300,
    ];

    $response = $this->post(route('admin.tours.store'), $data);

    $response->assertStatus(403);
    $response->assertJson([
        'message' => 'You are not authorized to create tourssss',
    ]);
});

test('tours by travel slug return only active travels', function () {
    $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);
    $tour = Tour::factory()->create(['travelId' => $travel->id, 'price' => 11111111]);

    $response = $this->get(route('tours_by_travel_slug', [$travel]));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'id' => $tour->id,
            'travelId' => $travel->id,
            'price' => 11111111,
        ]);

    $travel->update(['visibility' => TravelVisibilityEnum::PRIVATE]);

    $response = $this->get(route('tours_by_travel_slug', [$travel]));

    $response->assertStatus(403)
        ->assertJson([
            'message' => TravelIsNotPublicException::ERROR_MESSAGE,
        ]);
});

test('tours by travel slug return correct data', function () {
    $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);
    $tour = Tour::factory()->create(['travelId' => $travel->id, 'price' => 11111111]);

    $response = $this->get('/api/v1/travel/'.$travel->slug.'/tours');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'id' => $tour->id,
            'travelId' => $travel->id,
            'price' => 11111111,
        ]);
});

test('tours by travel slug with price from filter', function () {
    $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);
    $tour = Tour::factory()->create(['travelId' => $travel->id, 'price' => 100]);

    $response = $this->get('/api/v1/travel/'.$travel->slug.'/tours?priceFrom=50');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'id' => $tour->id,
            'travelId' => $travel->id,
            'price' => 100,
        ]);
});

test('tours by travel slug with price to filter', function () {
    $tour = Tour::factory()->create(['price' => 250]);

    $travel = $tour->travel;
    $response = $this->get('/api/v1/travel/'.$travel->slug.'/tours?priceTo=260');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'id' => $tour->id,
            'travelId' => $travel->id,
            'price' => 250,
        ]);
});

test('tours by travel slug with date from filter', function () {
    $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);
    $tour = Tour::factory()->create(['travelId' => $travel->id, 'startingDate' => '2022-01-01']);

    $response = $this->get('/api/v1/travel/'.$travel->slug.'/tours?dateFrom=2022-01-01');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'id' => $tour->id,
            'travelId' => $travel->id,
            'startingDate' => '2022-01-01',
        ]);
});

test('tours by travel slug with date to filter', function () {
    $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);
    $tour = Tour::factory()->create(['travelId' => $travel->id, 'startingDate' => '2022-01-01']);

    $response = $this->get('/api/v1/travel/'.$travel->slug.'/tours?dateTo=2022-01-01');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'id' => $tour->id,
            'travelId' => $travel->id,
            'startingDate' => '2022-01-01',
        ]);
});

test('tours by travel slug with sort by and sort order', function () {
    $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);
    $tour1 = Tour::factory()->create(['travelId' => $travel->id, 'price' => '100']);
    $tour2 = Tour::factory()->create(['travelId' => $travel->id, 'price' => '200']);

    $response = $this->get('/api/v1/travel/'.$travel->slug.'/tours?sortBy=price&sortOrder=desc');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.id', $tour2->id)
        ->assertJsonPath('data.1.id', $tour1->id);
});

test('tours pagination', function () {
    $paginateSize = (int) config('myconstants.tours.paginate');

    $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);

    Tour::factory()->count($paginateSize + 1)->create(['travelId' => $travel->id]);

    $response = $this->get('/api/v1/travel/'.$travel->slug.'/tours?page=2');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('meta.current_page', 2)
        ->assertJsonPath('meta.per_page', $paginateSize)
        ->assertJsonPath('meta.last_page', 2);
});
