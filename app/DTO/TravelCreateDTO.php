<?php

namespace App\DTO;

use Illuminate\Http\Request;
use App\Traits\ObjectToArray;
use App\Enums\TravelVisibilityEnum;

class CreateTravelDTO
{
    use ObjectToArray;

    public function __construct(
        public string $name,
        public string $description,
        public int $numberOfDays,
        public TravelVisibilityEnum|string $visibility,
        public array $moods,
        public ?array $images,
    )
    {}

    public static function fromRequest(Request $request): CreateTravelDTO
    {

        return new self(
            name: $request->name,
            description: $request->description,
            numberOfDays: $request->numberOfDays,
            visibility: $request->visibility,
            moods: $request->moods,
            images: $request->images,
        );
    }

    public static function fromArray(array $data): CreateTravelDTO
    {

        return new self(
            name: $data['name'],
            description: $data['description'],
            numberOfDays: $data['numberOfDays'],
            visibility: $data['visibility'],
            moods: $data['moods'],
            images: $data['images'],
        );
    }

    public function getTravelFields()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'numberOfDays' => $this->numberOfDays,
            'visibility' => $this->visibility,
        ];
    }


}
