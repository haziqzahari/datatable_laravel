<?php

namespace Haziqzahari\Laraveldatatable\Helpers;

use Haziqzahari\Laraveldatatable\Interfaces\WithLogging;

trait UseLogging
{
    /**
     * @return bool
     */
    public static function enableLogging(): bool
    {
        return false;
    }
}
