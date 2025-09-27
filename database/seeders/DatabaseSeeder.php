<?php

namespace Database\Seeders;


use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoomSeeder;
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
            UserSeeder::class,
            CustomerServiceSeeder::class,
            ContractTypeSeeder::class
        ]);
    }
}
