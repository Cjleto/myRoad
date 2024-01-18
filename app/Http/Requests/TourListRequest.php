<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TourListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'priceFrom' => 'numeric',
            'priceTo' => 'numeric',
            'dateFrom' => 'date_format:Y-m-d',
            'dateTo' => 'date_format:Y-m-d',
            'sortBy' => 'in:price',
            'sortOrder' => 'in:asc,desc',
        ];
    }

    /**
     * scribe section
     */
    public function queryParameters(): array
    {
        return [
            'priceFrom' => [
                'description' => 'The minimum price of the tour',
                'example' => 100,
            ],
            'priceTo' => [
                'description' => 'The maximum price of the tour',
                'example' => 10000,
            ],
            'dateFrom' => [
                'description' => 'The minimum starting date of the tour',
                'example' => '2024-01-01',
            ],
            'dateTo' => [
                'description' => 'The maximum starting date of the tour',
                'example' => '2024-12-31',
            ],
            'sortBy' => [
                'description' => 'The field to sort by',
                'example' => 'price',
            ],
            'sortOrder' => [
                'description' => 'The order to sort by',
                'example' => 'asc',
            ],
        ];
    }
}
