<?php

namespace App\Enums;

enum ActiveEnum: string
{
    case YES = '1';
    case NO = '0';

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
