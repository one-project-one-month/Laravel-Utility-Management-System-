<?php

namespace Database\Seeders;


use App\Models\ContractType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pricePerMonth = 800000.00;
        $price = $pricePerMonth; // init price with a month value

        $facilities = DB::raw("ARRAY['Smart Tv','Washing Machine','Air-con']");

        $contracts = [];

        for ($month = 3; $month <= 36; $price += $pricePerMonth) {
            $contracts[] = [
                'name'       => $month . ' months',
                'duration'   => $month,
                'price'      => $price,
                'facilities' => $facilities,
            ];

            // increase by a year (12 month) starting month reaches 12 months,
            // otherwise increase by 3 months
            $month = $month >= 12 ? $month + 12 : $month + 3;
        }

        foreach($contracts as $contract) {
            ContractType::create($contract);
        }
    }
}
