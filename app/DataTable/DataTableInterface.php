<?php

namespace App\DataTable;

interface DataTableInterface {

    //Used for custom query
    public function queryDataTable();
    public function queryTable();
    public function queryColumns();
    public function queryJoins();
    public function queryConditions();

    // Used for mapping row data
    public function mapDataTable();

}


