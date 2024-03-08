<?php

namespace App\DTO;

use App\Models\Travel;
use App\Enums\TravelVisibilityEnum;

readonly class TravelDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public int $numberOfDays,
        public TravelVisibilityEnum|string $visibility,
    )
    {}

    public static function fromArray(array $data): TravelDTO
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            description: $data['description'],
            numberOfDays: $data['numberOfDays'],
            visibility: $data['visibility'],
        );
    }

    public static function fromModel(Travel $travel): TravelDTO
    {
        return new self(
            id: $travel->id,
            name: $travel->name,
            description: $travel->description,
            numberOfDays: $travel->numberOfDays,
            visibility: $travel->visibility,
        );
    }
}
