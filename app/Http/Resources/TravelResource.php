<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Travel
 */
class TravelResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'numberOfDays' => $this->numberOfDays,
            'numberOfNight' => $this->numberOfNight,
            'moods' => $this->whenLoaded('moods', function () {
                return $this->flat($this->moods);
            }),
            'images'  => $this->getMedia('images')->map(function ($item) {
                return [
                    'url' => $item->getFullUrl(),
                    'name' => $item->name,
                    'size' => $item->size,
                    'mime_type' => $item->mime_type,
                ];
            }),
        ];

        return $array;
    }

    private function flat($resource): array
    {
        return $resource->mapWithKeys(function ($item) {
            return [$item['name'] => $item['pivot']['value']];
        })->toArray();
    }
}
