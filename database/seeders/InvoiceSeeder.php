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
        $bills = Bill::select('id','created_at','updated_at')->get();


        foreach($bills as $bill) {
            $number =  fake()->randomNumber(8, true);
            $customInvoice = "INV".'-'.$number;
            Invoice::create([
                    'invoice_no' => $customInvoice,
                    'bill_id' => $bill->id,
                    'status'    => "Paid",
                    'receipt_sent' => 1,
                    'created_at' => $bill->created_at,
                    'updated_at' => $bill->updated_at
            ]);
        }
    }
}
