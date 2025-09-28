<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TotalUnit;
use App\Models\Bill;

class TotalUnitSeeder extends Seeder
{
    public function run(): void
    {
        $bills = Bill::all();

        foreach ($bills as $bill) {
            TotalUnit::create([
                'bill_id' => $bill->id,
                'electricity_units' => rand(80, 150),
                'water_units' => rand(10, 25),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
