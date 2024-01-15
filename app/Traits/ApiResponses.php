<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiResponses
{
    protected function success($data = [], $status = 200)
    {
        return response([
            'success' => true,
            'data' => $data,
        ], $status);
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
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], $status));
    }
}
