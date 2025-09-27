<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i <= 10; $i++) {
            Room::create([
                'id' => (String) Str::uuid(),
                'room_no' => 100 + $i,
                'dimension' => (rand(200, 500)) . ' sqft',
                'no_of_bed_room' => rand(1, 4),
                'status' => 'Avaliable',
                'selling_price' => rand(5000000, 20000000),
                'max_no_of_people' => rand(1, 6),
                'description' => 'This is a description for Room ' . 100 + $i
            ]);
        }
    }
}
