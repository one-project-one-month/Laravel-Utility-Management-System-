<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invoices = Invoice::with(['bill'])->get();

        foreach ($invoices as $invoice) {
            $paidDate = fake()->dateTimeBetween( $invoice->created_at, $invoice->created_at->copy()->addDays(10));
            Receipt::create([
                'invoice_id' => $invoice->id,
                'payment_method' => Arr::random(['Cash','Mobile Banking']),
                'paid_date' =>  $paidDate ,
                'created_at' => $invoice->created_at,
                'updated_at' =>  $paidDate
            ]);
        }
    }
}
