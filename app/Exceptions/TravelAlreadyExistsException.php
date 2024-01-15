<?php

namespace App\Exceptions;

use Exception;

class TravelAlreadyExistsException extends Exception
{
    public function report ()
    {
        activity()
            ->useLog('travelCreation')
            ->withProperties(
                [
                    'level' => 'error',
                    'exception' => $this->getMessage(),
                    'request' => request()->path(),
                ]
            )
            ->causedBy(auth()->user() ?? null)
            ->log('Travel arleady exists');
    }

    public function render ($request)
    {
        return response()->json(
            [
                'message' => $this->getMessage(),
            ],
            409
        );
    }
}
