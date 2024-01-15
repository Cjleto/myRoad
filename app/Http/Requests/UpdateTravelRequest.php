<?php

namespace App\Http\Requests;

use App\Enums\TravelVisibilityEnum;
use App\Models\Travel;
use App\Rules\MoodsExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\UnauthorizedException;

class UpdateTravelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->tokenCan('can_update_travels');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
                new MoodsExistsRule(),
            ],
        ];
    }

    protected function failedAuthorization()
    {
        throw new UnauthorizedException('You are not authorized to update travels', 403);
    }
}
