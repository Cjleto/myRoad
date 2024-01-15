<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Exception;

class TravelNotFoundException extends Exception
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
            ->log('Travel not found');
    }
}
