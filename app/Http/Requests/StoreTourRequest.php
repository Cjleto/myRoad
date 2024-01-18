<?php

namespace App\Http\Requests;

use App\Exceptions\CustomException;
use App\Models\Travel;
use App\Services\TourService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreTourRequest extends FormRequest
{
    /* public function __construct(private TourService $tourService)
    {
    } */

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->tokenCan('can_create_tours');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $rules = [
            'travelId' => ['required', 'string', 'exists:travels,id'],
            'startingDate' => ['required', 'date_format:Y-m-d'],
            'endingDate' => ['required', 'date_format:Y-m-d', 'after:startingDate'],
            'price' => ['required', 'integer', 'min:1'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'travelId.exists' => 'The travelId field must exist in the travels table',
        ];
    }

    // check that the period has the same number of days as the travel
    protected function passedValidation()
    {
        $travel = Travel::find($this->travelId);
        if (! (new TourService)->validCreatingPeriod($travel, $this)) {
            throw CustomException::unprocessableContent('The period of the tour must be the same as the travel: '.$travel->numberOfDays.' days, you given '.Carbon::parse($this->startingDate)->diffInDays(Carbon::parse($this->endingDate)).' days');
        }
    }

    protected function failedAuthorization()
    {
        throw CustomException::unauthorized('You are not authorized to create tours');
    }

    /**
     * scribe section
     */
    public function bodyParameters()
    {
        return [
            'travelId' => [
                'description' => 'The id of the travel.',
                'example' => 'cbf304ae-a335-43fa-9e56-811612dcb601',
            ],
            'startingDate' => [
                'description' => 'The starting date of the tour.',
                'example' => '2024-01-01',
            ],
            'endingDate' => [
                'description' => 'The ending date of the tour.',
                'example' => '2024-01-08',
            ],
            'price' => [
                'description' => 'The price of the tour.',
                'example' => '1000',
            ],
        ];
    }
}
