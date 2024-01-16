<?php

namespace App\Exceptions;

class TravelNotFound extends CustomException
{
    public function __construct($message = 'Travel not found', $code = 404)
    {
        parent::__construct($message, $code);
    }
}
