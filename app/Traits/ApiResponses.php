<?php

namespace App\Traits;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiResponses
{
    protected function success($data = [], $status = 200)
    {
        $responseData = [
            'success' => true,
            'data' => $data,
        ];

        return response($responseData, $status);
    }

    protected function failure(Exception $exception, $status)
    {

        $data = [
            'success' => false,
            'message' => $exception->getMessage(),
        ];

        if (config('app.debug')) {
            $data['exception'] = $exception->getTrace();
        }

        return response($data, $status);
    }

    protected function validationFailure(Validator $validator, $status = 422)
    {
        throw new HttpResponseException(response()
            ->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], $status));
    }
}
