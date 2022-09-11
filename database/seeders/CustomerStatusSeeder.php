<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customer_statuses')->insert([
            'status_description' => 'ACTIVE'
        ]);

        DB::table('customer_statuses')->insert([
            'status_description' => 'INACTIVE'
        ]);
    }
}
