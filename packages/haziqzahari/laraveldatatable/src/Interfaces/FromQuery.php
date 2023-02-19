<?php

namespace Haziqzahari\Laraveldatatable\Interfaces;

interface FromQuery {

    /**
     * @return string
     */
    public function queryTable();

    /**
     * @return string
     */
    public function idColumn();

    /**
     * @return array
     */
    public function queryColumns();

    /**
     * @return array
     */
    public function queryConditions();

    /**
     * @return array
     */
    public function queryJoins();

    /**
     * @return array
     */
    public function queryGroup();
    
}
