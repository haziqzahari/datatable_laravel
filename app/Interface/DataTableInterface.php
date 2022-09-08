<?php

interface DataTableInterface {

    //To initiate DataTable
    public function getDataTable();

    //Used for custom query
    public function query();
    public function queryTable();
    public function queryColumns();
    public function queryJoins();
    public function queryConditions();

    //To map data in a row
    public function map();

}
