<?php

namespace App\DataTable;

use Haziqzahari\Laraveldatatable\Helpers\UseActions;
use Haziqzahari\Laraveldatatable\Helpers\UseFormatting;
use Haziqzahari\Laraveldatatable\Helpers\UseIndexing;
use Haziqzahari\Laraveldatatable\Helpers\UseLogging;
use Haziqzahari\Laraveldatatable\Helpers\UseModel;
use Haziqzahari\Laraveldatatable\Helpers\UseQuery;
use Haziqzahari\Laraveldatatable\Helpers\UseStyles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class DataTable
{
    use UseActions,
    UseFormatting,
    UseIndexing,
    UseLogging,
    UseModel,
    UseQuery,
    UseStyles;

    private $search;
    private $limit;
    private $offset;
    private $column;
    private $direction;
    private $draw;
    private $order;
    private $params;

    protected $action = array(
        'view' => array('enabled' => 0, 'route' => []),
        'edit' => array('enabled' => 0, 'route' => []),
        'delete' => array('enabled' => 0, 'route' => []),
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
     * Set the order for DataTable sorting
     *
     * @return void
     */
    public function setParams($params)
    {
        $this->params = $params;
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
    public function setActionRoute($view_route = [], $edit_route = [], $delete_route = [])
    {
        $this->action['view']['route'] = $view_route;
        $this->action['edit']['route'] = $edit_route;
        $this->action['delete']['route'] = $delete_route;
    }


    /**
     * Set the mapping for data returned.
     *
     * @return array
     */
    public function searchColumns(): array
    {
        return [];
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
     * Set the mapping for status columns.
     * Uses Bootstrap badges.
     *
     * Format  : array(
     *      'column' => array(
     *          'warning' => [value => '', text => ''],
     *          'danger' => [value => '', text => ''],
     *          'success' => [value => '', text => '']
     *      )
     * )
     * @return array
     */
    public function statusColumns(): array
    {
        return [];
    }

    /**
     * Set the mapping for status columns.
     * Uses Bootstrap badges.
     * @return array
     */
    public function idColumn(): string
    {
        return '';
    }

    /**
     * Set the mapping for date columns for formatting.
     * Uses date()  function to forrmat.
     * [
     *     'column_name'=> 'date_format'
     * ]
     * @return array
     */
    public function dateColumns(): array
    {
        return [];
    }

    /**
     * Set the mapping for date columns for formatting.
     * Uses date()  function to forrmat.
     * [
     *     'column_name'=> 'date_format'
     * ]
     * @return array
     */
    public function logContent(): string
    {
        return '';
    }





    /**
     * Map the columns of the table specified.
     * (Only for MySQL)
     *
     *
     * @return array
     */
    private function mapQueryColumns(): array
    {
        return array_map(function ($column, $key) {
            return $column;
        }, $this->queryColumns(), array_keys($this->queryColumns()));
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
                $q->where($columns instanceof DB ? ((array)$value)["Field"] : (is_string($key) ? $key : $value), 'like', '%' . $this->search . '%');
                continue;
            }

            $q->orWhere($columns instanceof DB ? ((array)$value)["Field"] : (is_string($key) ? $key : $value), 'like', '%' . $this->search . '%');
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
                $this->queryColumns()[(int)$this->order['column'] - 1],
            $this->order['direction']
        );
    }

    /**
     * Map orders for query builder.
     *
     *
     * @return void
     */
    private function mapQueryOrders()
    {
        array_map(function ($order) {
            $this->query->orderBy($order['column'], $order['direction']);
        }, $this->queryOrder());
    }

    /**
     * Map data to be returned.
     *
     *
     * @return array
     */
    private function mapRows($data): array
    {
        if (empty($this->mapDataTable())) {
            return $data;
        }

        $mapped = array();

        return array_map(function ($column, $index) use ($mapped) {
            $column = (array)$column;

            $id = '';


            foreach ($this->mapDataTable() as $key => $value) {

                if ($this->idColumn() != '') {
                    $id = $column[$this->idColumn()];
                }

                if ($key == 0 && $this->enableIndexing()) {
                    $mapped[] = $index + 1 + $this->offset;
                }

                if (str_contains(strtolower($value), 'status') && array_key_exists($value, $this->statusColumns())) {
                    $mapped[] = $this->mapStatusColumns($this->statusColumns()[$value], $column[$value]);

                    if (($key + 1) == count($this->mapDataTable())) {
                        $mapped[] =  $this->mapActionButton($id, $column);
                    }

                    continue;
                }

                if (array_key_exists($value, $this->dateColumns())) {
                    $mapped[] =  $this->mapDateColumns($column[$value], $this->dateColumns()[$value]);

                    if (($key + 1) == count($this->mapDataTable())) {
                        $mapped[] =  $this->mapActionButton($id, $column);
                    }

                    continue;
                }

                $mapped[] = $column[$value];

                if (($key + 1) == count($this->mapDataTable())) {
                    $mapped[] =  $this->mapActionButton($id, $column);
                }
            }

            return $mapped;
        }, $data, array_keys($data));
    }


    /**
     * Renders Status Badges
     * Using Bootstrap 5 Badges
     *
     */
    private function mapStatusColumns($status_column, $status): string
    {
        switch (true) {
            case (array_key_exists('success', $status_column) &&  $status_column['success']['value'] == strtolower($status)):
                $status = "<span class='badge bg-success text-light'>{$status_column['success']['text']}</span>";
                break;
            case (array_key_exists('warning', $status_column) &&  $status_column['warning']['value'] == strtolower($status)):
                $status = "<span class='badge bg-warning text-light'>{$status_column['warning']['text']}</span>";
                break;
            case (array_key_exists('danger', $status_column) &&  $status_column['danger']['value'] == strtolower($status)):
                $status = "<span class='badge bg-danger text-light'>{$status_column['danger']['text']}</span>";
                break;
            default:
                # code...
                break;
        }
        return $status;
    }

    /**
     * Renders Status Badges
     * Using Bootstrap 5 Badges
     *
     */
    private function mapDateColumns($date, $format): string
    {
        return date($format, strtotime($date));
    }

    /**
     * Renders Status Badges
     * Using Bootstrap 5 Badges
     *
     */
    protected function mapActionButton($id, $data): string
    {
        $action = '';


        if ($this->action['view']['enabled'] == true) {

            $view = [];


            foreach (explode("|", $this->action['view']['route'][key($this->action['view']['route'])]) as  $value) {
                $view[$value] = $data[$value];
            }

            if (strpos(key($this->action['view']['route']), '#') === 0) {
                $action .= '<a class="' . substr(key($this->action['view']['route']), 1) . ' mr-4 text-dark" href="#" data-toggle="tooltip" data-trigger="hover"  data-target="' . substr(key($this->action['view']['route']), 1) . '" data-placement="top"  data-id="' . $data[$this->action['view']['route']] . '"  data-html="true" title="View Details"><i class="fa-solid fa-eye"></i></a>';
            } else {


                $action .= '<a class="mr-4 text-dark" href="' . route(key($this->action['view']['route']), $view) . '" data-toggle="tooltip" data-trigger="hover"  data-placement="top" data-html="true" title="View Details"><i class="fa-solid fa-eye"></i></a>';
            }
        }

        if ($this->action['edit']['enabled'] == true) {

            $edit = [];

            foreach (explode("|", $this->action['edit']['route'][key($this->action['edit']['route'])]) as  $value) {
                $edit[$value] = $data[$value];
            }

            if (strpos(key($this->action['edit']['route']), '#') === 0) {
                $action .= '<a class="' . substr(key($this->action['edit']['route']), 1) . ' mr-4 text-dark" href="#" data-toggle="tooltip" data-trigger="hover"  data-target="' . substr(key($this->action['edit']['route']), 1) . '" data-placement="top" data-id="' . $data[$this->action['edit']['route'][key($this->action['edit']['route'])]] . '" data-html="true" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';
            } else {
                $action .= '<a class="mr-4 text-dark" href="' . route(key($this->action['edit']['route']), $edit) . '"><i class="fa-solid fa-pen-to-square" data-toggle="tooltip" data-trigger="hover"  data-placement="top" data-html="true" title="Edit"></i></a>';
            }
        }

        if ($this->action['delete']['enabled'] == true) {

            $delete = [];

            foreach (explode("|", $this->action['delete']['route'][key($this->action['delete']['route'])]) as  $value) {
                $delete[$value] = $data[$value];
            }



            $action .= '<a class="mr-4 text-dark" href="' . route(key($this->action['delete']['route']), $delete) . '"><i class="fa-solid fa-trash-can" data-toggle="tooltip" data-trigger="hover"  data-placement="top" data-html="true" title="Delete"></i></a>';
        }


        if ($this->enableLogging()) {
            $action .=  sprintf(
                '<span data-toggle="popover" data-trigger="hover"  data-placement="left" data-html="true" data-content="%s" data-original-title="Info"><i class="fa-solid fa-circle-info p-2" ></i></span>',
                $this->mapContent($data)
            );
        }

        return $action;
    }

    protected function mapContent($data)
    {
        if ($this->logContent() == '') {
            return 'No logs for this information.';
        }

        $text = '';

        foreach ($this->logContent() as $key => $content) {
            $text .=  vsprintf($content['text'], array_map((function ($index) use ($data) {
                return $data[$index];
            }), $content['map']));;
        }

        return $text;
    }

    /**
     * Run the query builder.
     *
     *
     * @return array
     */
    private function renderDataTable(): array
    {
        $data = $this->queryDataTable()->get();

        if (method_exists($this, 'beforeRender')) {
            $this->beforeRender($data);
        }

        $data = $data->toArray();

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
        return (int)($this->query_count->first() == null ? 0 : $this->query_count->first()->count);
    }
}
