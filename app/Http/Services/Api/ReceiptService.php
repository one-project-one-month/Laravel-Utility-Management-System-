<?php

namespace App\Http\Services\Api;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Http\Services\Api\MailService;

Class ReceiptService {

    protected MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function sentReceipt($receiptId) {
      $receipt = Receipt::with('invoice.bill')->find($receiptId);

       $user = User::where('tenant_id',$receipt->invoice->bill->tenant_id)->first();
       $this->mailService->send(
            [
                'username'    => $user->name,
                'rental'      => $receipt->invoice->bill->rental_fee,
                'electricity' => $receipt->invoice->bill->electricity_fee,
                'water'       => $receipt->invoice->bill->water_fee,
                'internet'    => $receipt->invoice->bill->wifi_fee,
                'other'       => $receipt->invoice->bill->fine_fee + $receipt->invoice->bill->service_fee + $receipt->invoice->bill->ground_fee + $receipt->invoice->bill->car_parking_fee,
                'total'       => $receipt->invoice->bill->total_amount,
                'due_date'    => $receipt->invoice->bill->due_date,
                'invoice_no'  => $receipt->invoice->invoice_no,
                'invoice_status' =>  $receipt->invoice->status,
                'paid_date'    => $receipt->paid_date,
                'payment_method'   =>  $receipt->payment_method
            ],
            $user->email,
            "Utility Receipt - " . \Carbon\Carbon::now()->format('F'),
            "receipt-report"
        );

        $invoice =     $receipt->invoice;
        $this->statusChange($invoice);

    }

    private function statusChange($invoice) {
         $invoice->receipt_sent = 1;
         $invoice->save();
    }
}

