<?php

namespace Haziqzahari\Laraveldatatable\Interfaces;

interface FromModel{
    /**
     * @return static
     */
    public function modelClass() : string;

    /**
     * @return array
     */
    public function modelColumns();

    /**
     * @return array
     */
    public function modelRelations();

    /**
     * @return array
     */
    public function modelConditions();

    /**
     * @return array
     */
    public function modelGroup();
}
