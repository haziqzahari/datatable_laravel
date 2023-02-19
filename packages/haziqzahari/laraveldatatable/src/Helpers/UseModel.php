<?php

namespace Haziqzahari\Laraveldatatable\Helpers;

use Haziqzahari\Laraveldatatable\Interfaces\FromModel;

trait UseModel
{
    /**
     * @return void
     */
    protected function modelQuery(): void
    {
    }

    /**
     * @return string
     */
    public function modelClass(): string
    {
        return '';
    }

    /**
     * @return array
     */
    public function modelColumns(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function modelRelations(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function modelConditions(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function modelGroup(): array
    {
        return [];
    }
}
