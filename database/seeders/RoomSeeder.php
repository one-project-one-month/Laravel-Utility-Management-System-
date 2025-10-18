<?php

namespace Database\Seeders;

use App\Enums\RoomStatus;
use App\Models\Room;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = [RoomStatus::Available, RoomStatus::Rented, RoomStatus::Purchased, RoomStatus::InMaintenance];

        for($i = 1; $i <= 30; $i++) {
            Room::create([
                'id'               => Str::uuid()->toString(),
                'room_no'          => 100 + $i,
                'floor'            => rand(1,10),
                'dimension'        => rand(200, 500) . ' sqft',
                'no_of_bed_room'   => rand(1, 4),
                'status'           => Arr::random($status)->value,
                'selling_price'    => rand(5000000, 20000000),
                'max_no_of_people' => rand(1, 6),
                'description'      => 'This is a description for Room ' . 100 + $i
            ]);
        }
    }
}
