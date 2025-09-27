<?php

namespace Database\Seeders;

use App\Models\CustomerService;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerServices = [
            // "room_id"  => 101,
            "category" => "Complain",
            "description" => "hello",
            "status" => "Pending",
            "priority_level" => "Medium",
            "issued_date"   => fake()->dateTimeBetween('2020-01-01', 'now')
        ];

        CustomerService::create($customerServices);
    }
}
