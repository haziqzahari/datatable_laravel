<?php

namespace Haziqzahari\Laraveldatatable\Helpers;

use Haziqzahari\Laraveldatatable\Interfaces\WithActions;

trait UseActions
{
    /**
     * @return array
     *
     */
    public function defaultActions(): array
    {
        return [];
    }

    /**
     * @return void
     *
     */
    protected function mapActionButton(): void
    {
    }
}
