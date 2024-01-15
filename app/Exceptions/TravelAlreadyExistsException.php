<?php

namespace App\Exceptions;

use App\Exceptions\CustomException;

class TravelAlreadyExistsException extends CustomException
{

    public function __construct ($message = 'Travel arleady exists', $code = 409)
    {
        parent::__construct($message, $code);
    }


}
