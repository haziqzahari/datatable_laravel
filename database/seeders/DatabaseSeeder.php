<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerStatus;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call([
            CustomerStatusSeeder::class
        ]);

        Customer::factory(10000)->create();
    }
}
