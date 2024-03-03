<?php

namespace App\Traits;

trait ObjectToArray
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
