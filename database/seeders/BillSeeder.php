<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bill;
use App\Models\Room;
use App\Models\User;


class BillSeeder extends Seeder
{
    public function run(): void
    {
        // Get all room UUIDs
        $rooms = Room::all();

        foreach ($rooms as $room) {
    $user = User::inRandomOrder()->first(); // assign a random user

    $bill = Bill::create([
        'room_id' => $room->id,
        'user_id' => $user->id,       // <- required
        'rental_fee' => rand(200, 500),
        'electricity_fee' => rand(50, 150),
        'water_fee' => rand(10, 30),
        'fine_fee' => rand(0, 50),
        'service_fee' => rand(20, 50),
        'ground_fee' => rand(10, 30),
        'car_parking_fee' => rand(0, 20),
        'wifi_fee' => rand(0, 15),
        'total_amount' => 0,
        'due_date' => now()->addDays(rand(5, 30)),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $bill->total_amount = $bill->rental_fee 
                         + $bill->electricity_fee
                         + $bill->water_fee
                         + ($bill->fine_fee ?? 0)
                         + $bill->service_fee
                         + $bill->ground_fee
                         + ($bill->car_parking_fee ?? 0)
                         + ($bill->wifi_fee ?? 0);
    $bill->save();
}

    }
}
