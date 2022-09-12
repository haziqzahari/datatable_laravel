<?php

namespace App\DataTable;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
trait DataTableTraits
{
    private $search;
    private $limit;
    private $offset;
    private $query;
    private $query_count;
    private $column;
    private $direction;
    private $draw;
    private $order;

    private $action = array(
        'view' => array('enabled' => 0, 'route' => ''),
        'edit' => array('enabled' => 0, 'route' => ''),
        'delete' => array('enabled' => 0, 'route' => ''),
    );

    public function __construct()
    {
    }

    /**
     * Return mapped data to DataTable
     *
     * @return array
     */
    public function getDataTable(): array
    {
        $data = $this->renderDataTable();

        $response = array(
            "draw" => $this->draw,
            "iTotalRecords" => $this->getDataCount(),
            "iTotalDisplayRecords" =>  $this->getDataCount(),
            "aaData" => $data
        );

        return $response;
    }


    /**
     * Set the search values for building query
     *
     * @return void
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }

    /**
     * Set the table pagination for building query
     *
     * @return void
     */
    public function setPage($limit, $offset)
    {
        $this->limit = (int)$limit;
        $this->offset = (int)$offset;
    }

    /**
     * Set the draw params required for rendering DataTable
     *
     * @return void
     */
    public function setDraw($draw)
    {
        $this->draw = $draw;
    }

    /**
     * Set the order for DataTable sorting
     *
     * @return void
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Set each action button to be enabled or disabled.
     * This feature is not yet available in this release version.
     *
     * @return void
     */
    public function setActionButton($view = 0, $edit = 0, $delete = 0)
    {
        $this->action['view']['enabled'] = $view;
        $this->action['edit']['enabled'] = $edit;
        $this->action['delete']['enabled'] = $delete;
    }

    /**
     * Set each action button route.
     * This feature is not yet available in this release version.
     *
     * @return void
     */
    public function setActionRoute($view_route = '', $edit_route = '', $delete_route = '')
    {
        $this->action['view']['route'] = $view_route;
        $this->action['edit']['route'] = $edit_route;
        $this->action['delete']['route'] = $delete_route;
    }

    /**
     * Query builder for DataTable
     *
     * @return object
     */
    public function queryDataTable()
    {
        $this->query = DB::table($this->queryTable())
            ->select($this->queryColumns());

        $this->query_count = DB::table($this->queryTable())
            ->selectRaw('COUNT(' . $this->queryTable() . '.' . ((array)($this->getTableColumns()[0]))["Field"] . ') as count');

        if (!empty($this->queryJoins())) {
            array_map(array($this, 'mapJoins'), $this->queryJoins());
        }

        if (!empty($this->queryConditions())) {
            array_map(array($this, 'mapConditions'), $this->queryConditions());
        }

        if (!empty($this->search)) {
            $this->query->where(function ($q) {
                return $this->mapSearchValue($q, $this->queryColumns()[0] == '*' ? $this->getTableColumns() : $this->queryColumns());
            });
            $this->query_count->where(function ($q) {
                return $this->mapSearchValue($q, $this->queryColumns()[0] == '*' ? $this->getTableColumns() : $this->queryColumns());
            });
        }

        if (!empty($this->order)) {
            $this->mapOrders();
        }

        if ($this->offset != 0) {
            $this->query->offset($this->offset);
        }

        $this->query->limit($this->limit);

        return $this->query;
    }

    /**
     * Set the DB table used for query builder.
     *
     * @return string
     */
    public function queryTable(): string
    {
        return '';
    }

    /**
     * Set the columns used for query builder in select().
     *
     * If there are joins involved, the tables for each columns
     * are to be specifed. If none, then column names only is sufficient.
     *
     * e.g. [
     * 'customers.customer_name',
     * 'customer_statuses.status_description'
     * ]
     *
     * @return array
     */
    public function queryColumns(): array
    {
        return ['*'];
    }

    /**
     * Set the joins used for query builder in join().
     *
     * Format : [
     * 'table' => '',
     * 'first_key' => '',
     * 'operator' => '',
     * 'second_key' => ''
     * ]
     *
     * @return array
     */
    public function queryJoins(): array
    {
        return [];
    }

    /**
     * Set the conditions used for query builder in join().
     *
     * Format : [
     * 'column' => '',
     * 'operator' => '',
     * 'value' => '',
     * 'type' => ''
     * ]
     *
     * @return array
     */
    public function queryConditions(): array
    {
        return [];
    }

    /**
     * Set the orders for query builder in orderBy().
     *
     * Format : [
     * 'column' => '',
     * 'direction' => '',
     *
     * @return array
     */
    public function queryOrder($order = []): array
    {
        return $order;
    }

    /**
     * Set the mapping for data returned.
     *
     * @return array
     */
    public function mapDataTable(): array
    {
        return [];
    }

    /**
     * Get the columns of the table specified.
     * (Only for MySQL)
     *
     *
     * @return array
     */
    private function getTableColumns(): array
    {
        return DB::select('SHOW COLUMNS FROM '.$this->queryTable());
    }

    /**
     * Map joins for query builder.
     *
     *
     * @return void
     */
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
                $this->query_count->rightJoin(
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
                $this->query_count->leftJoin(
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
                $this->query_count->join(
                    $join['table'],
                    $join['first_key'],
                    $join['operator'],
                    $join['second_key']
                );
                break;
        }
    }

    /**
     * Map conditions for query builder.
     *
     *
     * @return void
     */
    private function mapConditions($condition)
    {
        switch ($condition['type']) {
            case 'and':
                $this->query->where(
                    $condition['column'],
                    $condition['operator'],
                    $condition['value']
                );
                $this->query_count->where(
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
                $this->query_count->orWhere(
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
                $this->query_count->whereIn(
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
                $this->query_count->whereNotIn(
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
                $this->query_count->where(
                    $condition['column'],
                    $condition['operator'],
                    $condition['value']
                );
                break;
        }
    }

    /**
     * Map search values for query builder.
     *
     *
     * @return void
     */
    private function mapSearchValue($q, $columns)
    {
        foreach ($columns as $key => $value) {
            if ($key == 0) {
                $q->where(is_object($value) ? (array)$value["Field"]: $value, 'like', '%' . $this->search . '%');
                continue;
            }

            $q->orWhere(is_object($value) ? (array)$value["Field"]: $value, 'like', '%' . $this->search . '%');
        }

        return $q;
    }

     /**
     * Map orders for query builder.
     *
     *
     * @return void
     */
    private function mapOrders()
    {

        $this->query->orderBy(
            (int)$this->order['column'] == 0 ?
            ((array)($this->getTableColumns()[0]))["Field"] :
            $this->queryColumns()[(int)$this->order['column']-1],
            $this->order['direction']);
    }

    /**
     * Map data to be returned.
     *
     *
     * @return array
     */
    private function mapRows($data) : array
    {
        if (empty($this->mapDataTable())) {
            return $data;
        }

        $mapped = array();

        return array_map(function ($column, $index) use ($mapped) {
            $column = (array)$column;

            foreach ($this->mapDataTable() as $key => $value) {
                if ($key == 0) {
                    $mapped[] = $index + 1 + $this->offset;
                }
                $mapped[] = $column[$value];
            }

            return $mapped;
        }, $data, array_keys($data));
    }

    /**
     * Run the query builder.
     *
     *
     * @return array
     */
    private function renderDataTable(): array
    {
        $data = $this->queryDataTable()->get()->toArray();

        return $this->mapRows($data);
    }


    /**
     * Get row counts of the specified table.
     *
     *
     * @return void
     */
    private function getDataCount(): int
    {

        return (int)($this->query_count->get()->toArray()[0]->count);
    }
}
