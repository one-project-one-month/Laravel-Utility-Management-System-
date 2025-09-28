<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\InvoiceSeeder;
use Database\Seeders\TotalUnitSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoomSeeder::class,
            TenantSeeder::class,
            UserSeeder::class,
            CustomerServiceSeeder::class,
            ContractTypeSeeder::class,
            ContractSeeder::class,
            BillSeeder::class,
            TotalUnitSeeder::class,
            InvoiceSeeder::class
        ]);
    }
}
