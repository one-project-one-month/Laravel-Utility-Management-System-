<?php

namespace App\Http\Services\Api;

use Log;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\TotalUnit;

Class BillingService {

    public function generateBillForRoom($user) {

        $bill = Bill::create($this->createBill($user));

        $this->totalUnitsCreate($bill->id, $bill->electricity_fee, $bill->water_fee);

       $invoice = Invoice::create([
            'bill_id' => $bill->id,
        ]);

        Receipt::create([
             'invoice_id' => $invoice->id,
        ]);

    }

    private function createBill($user) {
        $today = Carbon::today();
        $dueDate = $today->copy()->addDays(10);

        $rental_fee = rand(5000000, 20000000);
        $electricity_fee = rand(5000, 100000);
        $water_fee = rand(5000, 50000);
        $fine_fee  = rand(0, 10000);
        $service_fee = 10000;
        $ground_fee = 5000;
        $car_parking_fee = rand(5000, 100000);
        $wifi_fee = rand(30000, 200000);
        $totalAmount = $rental_fee + $electricity_fee + $water_fee + $fine_fee
            + $service_fee + $ground_fee + $car_parking_fee + $wifi_fee;

        $data = [
            'room_id' => $user->tenant->room_id,
            'user_id' => $user->id,
            'rental_fee' => $rental_fee,
            'electricity_fee' => $electricity_fee,
            'water_fee' => $water_fee,
            'fine_fee' => $fine_fee,
            'service_fee' => $service_fee,
            'ground_fee' => $ground_fee,
            'car_parking_fee' => $car_parking_fee,
            'wifi_fee' => $wifi_fee,
            'total_amount' => $totalAmount,
            'due_date' => $dueDate
        ];

        return $data;
    }

    private function calculateElectricUnits($electricity_fee) {
            return $electricity_fee / config('units.electric');
    }

    private function calculateWaterUnits($water_fee) {
            return $water_fee / config('units.water');
    }

    private function totalUnitsCreate($billId,$electricity_fee,$water_fee) {
            TotalUnit::create([
                 'bill_id'           => $billId,
                 'electricity_units' => $this->calculateElectricUnits($electricity_fee),
                 'water_units' =>  $this->calculateWaterUnits($water_fee)
            ]);
    }


}
