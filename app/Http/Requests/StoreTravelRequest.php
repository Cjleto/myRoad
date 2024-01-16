<?php

namespace App\Http\Requests;

use App\Enums\TravelVisibilityEnum;
use App\Exceptions\CustomException;
use App\Rules\MoodsExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTravelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->tokenCan('can_create_travels');
    }

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

    protected function failedAuthorization()
    {
        throw CustomException::unauthorized('You are not authorized to create travels');
    }
}
