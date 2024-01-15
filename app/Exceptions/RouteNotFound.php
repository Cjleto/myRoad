<?php

namespace App\Exceptions;

use App\Exceptions\CustomException;

class RouteNotFound extends CustomException
{

    public function __construct ($message = 'Route not found', $code = 404)
    {
        parent::__construct($message, $code);
    }


}
