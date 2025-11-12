<?php

namespace App\Http\Services\Api;

use Log;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\TotalUnit;
use App\Http\Services\Api\MailService;

Class BillingService {

    protected MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function generateBillForRoom($user,$customInvoice) {

        $bill = Bill::create($this->createBill($user));

       $this->totalUnitsCreate($bill->id, $bill->electricity_fee, $bill->water_fee);

       $invoice = Invoice::create([
            'invoice_no' => $customInvoice,
            'status' => "Pending",
            'bill_id' => $bill->id
        ]);
        
        $receipt = Receipt::create([
            "invoice_id" => $invoice->id,
        ]);

        $this->mailService->send(
            [
                'username'    => $user->name,
                'rental'      => $bill->rental_fee,
                'electricity' => $bill->electricity_fee,
                'water'       => $bill->water_fee,
                'internet'    => $bill->wifi_fee,
                'other'       => $bill->fine_fee + $bill->service_fee + $bill->ground_fee + $bill->car_parking_fee,
                'total'       => $bill->total_amount,
                'due_date'    => $bill->due_date,
                'invoice_no'  => $invoice->invoice_no,
            ],
            $user->email,
            "Utility Alert - " . \Carbon\Carbon::now()->format('F'),
            "billing-report"
            );

    // $this->mailService->sendQueued([...], $user->email, "Utility Alert - " . \Carbon\Carbon::now()->format('F'), "billing-report");

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
            'tenant_id' => $user->tenant_id,
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
