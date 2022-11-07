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

    public function idColumn(): string
    {
        return 'customer_id';
    }

    public function queryColumns(): array
    {
        return [
            'customers.customer_id',
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

    public function statusColumns(): array
    {
        return [
            'status_description' => array(
                'success'  => 'active',
                'warning' => 'inactive'
            )
        ];
    }

    public function dateColumns(): array
    {
        return [
            'created_at' => 'd M-Y h:i A'
        ];
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

    public function logContent(): array
    {
        return [
            array(
               'text'=>"<small>Created by:</small><br>%s<br><small class=\'text-sm\'>%s</small><br><small class=\'text-sm\'>%s</small>",
               'map' => array(
                    'customer_name',
                    'customer_email',
                    'created_at'
               )
            )
        ];
    }
}
