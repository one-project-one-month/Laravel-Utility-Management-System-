<?php

namespace Database\Seeders;


use App\Models\ContractType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContractTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contracts = [
            [
            'name' => '3 months',
            'duration' => 3,
            'price'    => 800000.00,
            'facilities' => DB::raw("ARRAY['Smart Tv','Washing Machine','Aircon']"),
            ],
             [
            'name' => '6 months',
            'duration' => 6,
            'price'    => 1600000.00,
            'facilities' => DB::raw("ARRAY['Smart Tv','Washing Machine','Aircon']"),
            ],
             [
            'name' => '9 months',
            'duration' => 3,
            'price'    => 2400000.00,
            'facilities' => DB::raw("ARRAY['Smart Tv','Washing Machine','Aircon']"),
            ],
             [
            'name' => '12 months',
            'duration' => 3,
            'price'    => 3200000.00,
            'facilities' => DB::raw("ARRAY['Smart Tv','Washing Machine','Aircon']"),
            ],
            [
            'name' => '24 months',
            'duration' => 3,
            'price'    => 6400000.00,
            'facilities' => DB::raw("ARRAY['Smart Tv','Washing Machine','Aircon']"),
            ],
            [
            'name' => '36 months',
            'duration' => 3,
            'price'    => 9600000.00,
            'facilities' => DB::raw("ARRAY['Smart Tv','Washing Machine','Aircon']"),
            ],
        ];

        foreach($contracts as $contract) {
            ContractType::create($contract);
        }
    }
}
