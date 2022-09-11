<?php

namespace App\DataTable;

use App\DataTable\DataTableTraits;
use App\DataTable\DataTableInterface;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataTableCustomer implements DataTableInterface
{
    use DataTableTraits;

    public function queryTable()
    {
        return 'customers';
    }

    public function queryColumns(): array
    {
        return [
            'customers.customer_name',
            'customers.customer_phone',
            'customers.customer_email',
            'customer_statuses.status_description',
            'customers.created_at'
        ];
    }

    public function queryJoins(): array
    {
        return [
            array(
                'table' => 'customer_statuses',
                'first_key' => 'customers.status',
                'operator' => '=',
                'second_key' => 'customer_statuses.id',
                'type' => 'inner'
            )
        ];
    }

    public function queryConditions(): array
    {
        return [
            array(
                'column' => 'customers.status',
                'operator' => '=',
                'value' => '1',
                'type' => 'and'
            )
        ];
    }

    public function queryOrder($order = []): array
    {
        return $order;
    }

    public function mapDataTable()
    {
        return [
            'customer_name',
            'customer_phone',
            'customer_email',
            'status_description',
            'created_at'
        ];
    }
}
