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
}
