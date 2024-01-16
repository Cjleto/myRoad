<?php

namespace App\Exceptions;

class UnauthorizedException extends CustomException
{
    public function __construct($message = 'Unauthorized', $code = 401)
    {
        parent::__construct($message, $code);
    }
}
