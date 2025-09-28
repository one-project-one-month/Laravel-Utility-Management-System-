<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\BillSeeder;
use Database\Seeders\RoomSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\TenantSeeder;
use Database\Seeders\ContractSeeder;
use Database\Seeders\ContractTypeSeeder;
use Database\Seeders\CustomerServiceSeeder;


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
            BillSeeder::class
        ]);
    }
}
