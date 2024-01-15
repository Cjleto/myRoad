<?php

namespace App\Http\Requests;

use App\Traits\ApiResponses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{

    use ApiResponses;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' =>'required|email',
            'password' => 'required|string|min:8|max:20',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        /* throw new HttpResponseException(response()
        ->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 422)); */

        return $this->validationFailure($validator);
    }
}
