<?php

namespace Tests\Feature\Api\V1;

use App\Enums\TravelVisibilityEnum;
use App\Models\Tour;
use App\Models\Travel;
use Tests\TestCase;

class TourControllerTest extends TestCase
{
    /** @test */
    public function test_tours_by_travel_slug_return_correct_data(): void
    {
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
    }

    /** @test */
    public function test_tours_by_travel_slug_with_price_from_filter(): void
    {
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
    }

    /** @test */
    public function test_tours_by_travel_slug_with_price_to_filter(): void
    {
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
    }

    /** @test */
    public function test_tours_by_travel_slug_with_date_from_filter(): void
    {
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
    }

    /** @test */
    public function test_tours_by_travel_slug_with_date_to_filter(): void
    {
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
    }

    /** @test */
    public function test_tours_by_travel_slug_with_sort_by_and_sort_order(): void
    {
        $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);
        $tour1 = Tour::factory()->create(['travelId' => $travel->id, 'price' => '100']);
        $tour2 = Tour::factory()->create(['travelId' => $travel->id, 'price' => '200']);

        $response = $this->get('/api/v1/travel/'.$travel->slug.'/tours?sortBy=price&sortOrder=desc');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $tour2->id)
            ->assertJsonPath('data.1.id', $tour1->id);
    }

    /** @test */
    public function test_tours_pagination(): void
    {
        $paginateSize = (int) config('myconstants.tours.paginate');

        $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);

        Tour::factory()->count($paginateSize + 1)->create(['travelId' => $travel->id]);

        $response = $this->get('/api/v1/travel/'.$travel->slug.'/tours?page=2');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.current_page', 2)
            ->assertJsonPath('meta.per_page', $paginateSize)
            ->assertJsonPath('meta.last_page', 2);
    }
}
