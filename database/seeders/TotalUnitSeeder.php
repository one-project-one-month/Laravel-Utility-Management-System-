<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\TotalUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TotalUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bills = Bill::select('id','electricity_fee','water_fee','created_at','updated_at')->get();

        foreach ($bills as $bill) {
            TotalUnit::Create([
                    'bill_id'           => $bill->id,
                    'electricity_units' => $bill->electricity_fee / config('units.electric'),
                    'water_units'       => $bill->water_fee / config('units.water'),
                    'created_at'        => $bill->created_at,
                    'updated_at'        => $bill->updated_at
            ]);
        }
    }
}
