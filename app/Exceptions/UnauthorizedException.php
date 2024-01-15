<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Exception;

class UnauthorizedException extends Exception
{
    use ApiResponses;
    public function report ()
    {
        activity()
            ->withProperties(
                [
                    'level' => 'error',
                    'exception' => $this->getMessage(),
                ]
            )
            ->causedBy(auth()->user() ?? null)
            ->log('Unauthorized');
    }

    public function render ($request)
    {
        return response()->json(
            [
                'message' => $this->getMessage(),
            ],
            403
        );
    }
}
