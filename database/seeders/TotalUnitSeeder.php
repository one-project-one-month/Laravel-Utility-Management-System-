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
        $bills = Bill::select('id','electricity_fee','water_fee')->get();

        $electricUnit = config('units.electric', 1);
        $waterUnit = config('units.water', 1);

        foreach ($bills as $bill) {
            TotalUnit::Create([
                    'bill_id'           => $bill->id,
                    'electricity_units' => $electricUnit ? $bill->electricity_fee / $electricUnit : 0,
                    'water_units'       => $waterUnit ? $bill->water_fee / $waterUnit : 0,
            ]);
        }
    }
}
