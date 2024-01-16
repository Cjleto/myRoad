<?php

namespace Database\Factories;

use App\Enums\TravelVisibilityEnum;
use App\Models\Travel;
use App\Services\TravelService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{
    protected $model = Travel::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->sentence(4);

        return [
            'id' => $this->faker->uuid,
            'slug' => Str::slug($name, '-'),
            'name' => $name,
            'code' => TravelService::generateCode($name),
            'description' => $this->faker->paragraph,
            'numberOfDays' => $this->faker->numberBetween(1, 10),
            'visibility' => $this->faker->randomElement(TravelVisibilityEnum::getAllValues()),
        ];
    }
}
