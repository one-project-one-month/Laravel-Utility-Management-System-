<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\CustomerService;
use Illuminate\Database\Seeder;

class CustomerServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomIds = Room::pluck('id');

        for($i = 1; $i <= 20; $i++) {
            CustomerService::create(
                [
                    "room_id"        => $roomIds->random(),
                    "category"       => fake()->randomElement(['Complain','Maintenance','Other']),
                    "description"    => fake()->sentence(),
                    "status"         => fake()->randomElement(['Pending','Ongoing','Resolved']),
                    "priority_level" => fake()->randomElement(['Low', 'Medium', 'High']),
                    "issued_date"    => fake()->dateTimeBetween('2020-01-01')
                ]
            );
        }
    }
}
