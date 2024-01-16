<?php

namespace Database\Factories;

use App\Enums\TravelVisibilityEnum;
use App\Models\Travel;
use App\Services\TourService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    public function definition(): array
    {

        // set $travelId getting a random id from Travels table
        $travel = Travel::factory()->create(['visibility' => TravelVisibilityEnum::PUBLIC]);
        $startingDate = $this->faker->dateTimeThisYear()->format('Y-m-d');
        // endingDate is equal to startingDate + numberOfDays of travel
        $endingDate = date('Y-m-d', strtotime($startingDate.'+ '.$travel->numberOfDays.'days'));

        return [
            'id' => $this->faker->uuid,
            'travelId' => $travel->id,
            'name' => (new TourService)->generateName(
                $travel,
                [
                    'startingDate' => $startingDate,
                    'endingDate' => $endingDate,
                ]),
            'startingDate' => $startingDate,
            'endingDate' => $endingDate,
            'price' => $this->faker->numberBetween(10000, 500000),
            // Aggiungi altri campi se necessario
        ];
    }
}
