<?php

namespace Haziqzahari\Laraveldatatable\Helpers;

use Haziqzahari\Laraveldatatable\Interfaces\WithIndexing;

trait UseIndexing
{
    /**
     * @return bool
     */
    public static function enableIndexing() : bool {
        return false;
    }
}