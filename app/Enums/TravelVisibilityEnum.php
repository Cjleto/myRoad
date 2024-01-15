<?php

namespace App\Enums;

enum TravelVisibilityEnum: string
{
    case PUBLIC = 'public';
    case PRIVATE = 'private';

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
