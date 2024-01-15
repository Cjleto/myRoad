<?php

namespace App\Exceptions;

use App\Exceptions\CustomException;

class TravelNotFound extends CustomException
{

    public function __construct ($message = 'Travel not found', $code = 404)
    {
        parent::__construct($message, $code);
    }


}
