<?php

namespace App\Http\Resources;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Tour
 *
 * @property mixed $price
 */
class TourResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $array = [
            'id' => $this->id,
            'travelId' => $this->travelId,
            'name' => $this->name,
            'startingDate' => $this->startingDate,
            'endingDate' => $this->endingDate,
            'price' => $this->price,
        ];

        return $array;
    }

    public function with(Request $request)
    {
        return [
            'count' => Tour::count(),
        ];
    }
}
