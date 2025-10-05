<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Room;
use App\Models\User;
use Illuminate\Log\Logger;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $users = User::with(['tenant'])->where('role','Tenant')->get();

            foreach($users as $user) {
                $rental_fee = rand(5000000, 20000000);
                $electricity_fee = rand(5000, 100000);
                $water_fee =  rand(5000, 50000);
                $fine_fee  =  rand(0, 10000);
                $service_fee = 10000;
                $ground_fee = 5000;
                $car_parking_fee = rand(5000, 100000);
                $wifi_fee =  rand(30000, 200000);

                $totalAmount =  $rental_fee + $electricity_fee + $water_fee + $fine_fee + $service_fee + $ground_fee + $car_parking_fee + $wifi_fee ;
                Bill::create([
                    'room_id' => $user->tenant->room_id,
                    'tenant_id' =>  $user->tenant_id,
                    'rental_fee' => $rental_fee,
                    'electricity_fee' =>  $electricity_fee,
                    'water_fee' =>   $water_fee,
                    'fine_fee' =>  $fine_fee,
                    'service_fee' => $service_fee ,
                    'ground_fee'  => $ground_fee,
                    'car_parking_fee' =>   $car_parking_fee,
                    'wifi_fee' =>   $wifi_fee,
                    'total_amount' =>  $totalAmount,
                    'due_date' => fake()->dateTimeBetween('2020-01-01', 'now')
                ]);
            }
}
}
