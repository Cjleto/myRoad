<?php

namespace App\Http\Requests;

use App\Enums\TravelVisibilityEnum;
use App\Exceptions\CustomException;
use App\Rules\MoodsExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTravelRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
                'min:2',
                'max:2000',
            ],
            'numberOfDays' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],
            'visibility' => [
                'required',
                Rule::enum(TravelVisibilityEnum::class),
            ],
            'moods' => [
                'required',
                'array',
                'min:1',
                'max:1000',
                new MoodsExistsRule,
            ],
        ];
    }

    public function messages()
    {
        return [
            'visibility.*' => 'The visibility field is required and must be one of: '.implode(', ', TravelVisibilityEnum::getAllValues()),
        ];
    }

    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'The name of the travel',
                'example' => 'Jordan 361',
            ],
            'description' => [
                'description' => 'The description of the travel',
                'example' => 'Jordan 361°: the perfect tour to....',
            ],
            'numberOfDays' => [
                'description' => 'The number of days of the travel',
                'example' => 5,
            ],
            'visibility' => [
                'description' => 'The visibility of the travel (public or private)',
                'example' => 'public',
            ],
            'moods' => [
                'description' => 'The moods of the travel',
                'example' => '{"nature": 80,"relax": 20,"history": 90,"culture": 30,"party": 10}',
            ],
        ];
    }
}
