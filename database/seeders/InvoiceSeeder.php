<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $billIds = Bill::pluck('id');


        foreach($billIds as $billId) {
            $number =  fake()->randomNumber(8, true);
            $customInvoice = "INV".'-'.$number;
            Invoice::create([
                    'invoice_no' => $customInvoice,
                    'bill_id' => $billId
            ]);
        }
    }
}
