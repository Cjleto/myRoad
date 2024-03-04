<?php

namespace App\Exceptions\Travel;

use App\Exceptions\CustomException;

class TravelIsNotPublicException extends CustomException
{

    public const ERROR_MESSAGE = 'Travesl is not public';

    public function __construct($message = self::ERROR_MESSAGE, $code = 403)
    {
        parent::__construct($message, $code);
    }
}
