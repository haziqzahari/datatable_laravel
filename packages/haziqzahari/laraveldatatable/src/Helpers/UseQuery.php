<?php

namespace Haziqzahari\Laraveldatatable\Helpers;

use Haziqzahari\Laraveldatatable\Interfaces\FromQuery;
use Illuminate\Support\Facades\DB;

trait UseQuery
{
    protected $query;

    protected $query_count;

    /**
     * @return string
     */
    public function queryTable(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function idColumn(): string
    {
        return '';
    }

    /**
     * @return array
     */
    public function queryColumns(): array
    {
        return ['*'];
    }

    /**
     * @return array
     */
    public function queryConditions(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function queryJoins(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function queryGroup(): array
    {
        return [];
    }

    /**
     * Return SQL Query of Query Builder
     *
     * @return array
     */
    public function getQuerySQL(): string
    {
        return $response = $this->query->toSql();
    }


    /**
     * @return void
     */
    protected function queryDataTable(): void
    {
        $this->buildQuery();

        $this->buildCountQuery();

        if (!empty($this->queryJoins())) {
            array_map(array($this, 'mapJoins'), $this->queryJoins());
        }

        if (!empty($this->queryConditions())) {
            array_map(array($this, 'mapConditions'), $this->queryConditions());
        }

        if (!empty($this->search)) {
            $this->query->where(function ($q) {
                return $this->mapSearchValue($q, empty($this->searchColumns()) ? $this->getTableColumns() : $this->searchColumns());
            });
            $this->query_count->where(function ($q) {
                return $this->mapSearchValue($q, empty($this->searchColumns()) ? $this->getTableColumns() : $this->searchColumns());
            });
        }

        if (!empty($this->queryGroup())) {
            $this->query->groupBy($this->queryGroup());

            $this->query_count->groupBy($this->queryGroup());
        }

        if (!empty($this->queryOrder())) {
            $this->mapQueryOrders();
        }

        if (!empty($this->order)) {
            $this->mapOrders();
        }

        if ($this->offset != 0) {
            $this->query->offset($this->offset);
        }

        $this->query->limit($this->limit);
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
        return DB::select('SHOW COLUMNS FROM ' . $this->queryTable());
    }

    /**
     * @return void
     */
    private function buildQuery(): void
    {
        $this->query = DB::table($this->queryTable())
            ->select($this->mapQueryColumns());
    }

    /**
     * @return void
     */
    private function buildCountQuery(): void
    {
        $this->query_count = DB::table($this->queryTable())
            ->selectRaw('COUNT(' . $this->queryTable() . '.' . ((array)($this->getTableColumns()[0]))["Field"] . ') OVER () as count');
    }

    /**
     * Map joins for query builder.
     *
     *
     * @return void
     */
    private function mapJoins($join): void
    {
        switch ($join['type']) {
            case 'right':
                $this->query->rightJoin(
                    $join['table'],
                    function ($j) use ($join) {
                        $j->on(
                            $join['first_key'],
                            $join['operator'],
                            $join['second_key']
                        );

                        if (array_key_exists('condition', $join)) {
                            $j->whereRaw($join['condition']);
                        }
                    }
                );
                $this->query_count->rightJoin(
                    $join['table'],
                    function ($j) use ($join) {
                        $j->on(
                            $join['first_key'],
                            $join['operator'],
                            $join['second_key']
                        );

                        if (array_key_exists('condition', $join)) {
                            $j->whereRaw($join['condition']);
                        }
                    }
                );
                break;
            case 'left':
                $this->query->leftJoin(
                    $join['table'],
                    function ($j) use ($join) {
                        $j->on(
                            $join['first_key'],
                            $join['operator'],
                            $join['second_key']
                        );

                        if (array_key_exists('condition', $join)) {
                            $j->whereRaw($join['condition']);
                        }
                    }
                );
                $this->query_count->leftJoin(
                    $join['table'],
                    function ($j) use ($join) {
                        $j->on(
                            $join['first_key'],
                            $join['operator'],
                            $join['second_key']
                        );

                        if (array_key_exists('condition', $join)) {
                            $j->whereRaw($join['condition']);
                        }
                    }
                );
                break;
            case 'joinSub':
                $this->query->joinSub(
                    $join['subquery']
                );
                $this->query_count->joinSub(
                    $join['subquery']
                );
                break;
            default:
                $this->query->join(
                    $join['table'],
                    function ($j) use ($join) {
                        $j->on(
                            $join['first_key'],
                            $join['operator'],
                            $join['second_key']
                        );

                        if (array_key_exists('condition', $join)) {
                            $j->whereRaw($join['condition']);
                        }
                    }
                );
                $this->query_count->join(
                    $join['table'],
                    function ($j) use ($join) {
                        $j->on(
                            $join['first_key'],
                            $join['operator'],
                            $join['second_key']
                        );

                        if (array_key_exists('condition', $join)) {
                            $j->whereRaw($join['condition']);
                        }
                    }
                );
                break;
        }
    }
}
