<?php

namespace App\Exceptions;

class TravelAlreadyExistsException extends CustomException
{
    public function __construct($message = 'Travel arleady exists', $code = 409)
    {
        parent::__construct($message, $code);
    }
}
