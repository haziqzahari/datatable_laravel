<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
trait DataTable
{
    private $direction;
    private $index;
    private $columns;
    private $start;
    private $length;
    private $search;
    private $table;
    private $joins;


    private $query;

    private $action = array(
        'view' => array('enabled' => 0, 'route' => ''),
        'edit' => array('enabled' => 0, 'route' => ''),
        'delete' => array('enabled' => 0, 'route' => ''),
    );

    public function __construct()
    {
    }

    public function getDataTable()
    {
    }

    public function setTable(string $table)
    {
        $this->table = $table;
    }

    public function setJoins(array $joins)
    {
        $this->joins = $joins;
    }

    public function setSearchValue(string $search)
    {
        $this->search = $search;
    }

    public function setOrder($direction = 'asc')
    {
        $this->direction = $direction;
    }

    public function setColumn(int $index)
    {
        $this->index = $index;
    }

    public function setPage($start, $length)
    {
        $this->start = $start;
        $this->length = $length;
    }

    public function setActionButton($view = 0, $edit = 0, $delete = 0)
    {
        $this->action['view']['enabled'] = $view;
        $this->action['edit']['enabled'] = $edit;
        $this->action['delete']['enabled'] = $delete;
    }

    public function setActionRoute($view_route = '', $edit_route = '', $delete_route = '')
    {
        $this->action['view']['route'] = $view_route;
        $this->action['edit']['route'] = $edit_route;
        $this->action['delete']['route'] = $delete_route;
    }

    public function query()
    {
        $this->query = DB::table($this->queryTable())
            ->select($this->queryColumns());

        if (!empty($this->queryJoins())) {
            array_map(array($this, 'mapJoins'), $this->queryJoins());
        }

        if (!empty($this->queryConditions())) {
            array_map(array($this, 'mapConditions'), $this->queryJoins());
        }

        if (!empty($this->search))
        {
            $this->query->where(function($q){
                return $this->mapSearchValue($q, count($this->queryColumns()) > 1 ? $this->getTableColumns() : $this->queryColumns());
            });
        }

    }

    public function queryTable(): string
    {
        return '';
    }

    public function queryColumns(): array
    {
        return ['*'];
    }

    public function queryJoins(): array
    {
        return [];
    }

    public function queryConditions(): array
    {
        return [];
    }

    public function queryGroupBy(): array
    {
        return [];
    }


    private function getTableColumns(): array
    {
        return Schema::getColumnListing($this->queryTable());
    }

    private function mapJoins($join)
    {
        switch ($join['type']) {
            case 'right':
                $this->query->rightJoin(
                    $join['table'],
                    $join['first_key'],
                    $join['operator'],
                    $join['second_key']
                );
                break;
            case 'left':
                $this->query->leftJoin(
                    $join['table'],
                    $join['first_key'],
                    $join['operator'],
                    $join['second_key']
                );
                break;
            default:
                $this->query->join(
                    $join['table'],
                    $join['first_key'],
                    $join['operator'],
                    $join['second_key']
                );
                break;
        }
    }

    private function mapConditions($condition)
    {
        switch ($condition['type']) {
            case 'and':
                $this->query->where(
                    $condition['column'],
                    $condition['operator'],
                    $condition['value']
                );
                break;
            case 'or':
                $this->query->orWhere(
                    $condition['column'],
                    $condition['operator'],
                    $condition['value']
                );
                break;
            case 'in':
                $this->query->whereIn(
                    $condition['column'],
                    $condition['value'],
                    $condition['boolean']
                );
                break;
            case 'not in':
                $this->query->whereNotIn(
                    $condition['column'],
                    $condition['value'],
                    $condition['boolean']
                );
                break;
            default:
                $this->query->where(
                    $condition['column'],
                    $condition['operator'],
                    $condition['value']
                );
                break;
        }
    }

    private function mapSearchValue($q, $columns)
    {
        foreach ($columns as $key => $value) {
            if($key == 1)
            {
                $q->where($value, 'like', '%'.$this->search.'%');
                continue;
            }

            $q->orWhere($value, 'like', '%'.$this->search.'%');
        }

        return $q;
    }
}
