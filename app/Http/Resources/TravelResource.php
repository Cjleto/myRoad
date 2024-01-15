<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\TravelMoodResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $array = [
            "id" => $this->id,
            "name" => $this->name,
            "slug" => $this->slug,
            "description" => $this->description,
            "numberOfDays" => $this->numberOfDays,
            "numberOfNight" => $this->numberOfNight,
            "moods" => $this->whenLoaded('moods', function () {
                return $this->flat($this->moods);
            }),
        ];

        return $array;
    }

    private function flat($resource): array
    {
        /* $array = $resource ?? [];

        $result = [];
        foreach ($array as $item) {

            $tmp = [$item['name'] => $item['pivot']['value']];
            $result = array_merge($result, $tmp);
        }
        return $result; */

        return $resource->mapWithKeys(function ($item) {
            return [$item['name'] => $item['pivot']['value']];
        })->toArray();
    }
}
