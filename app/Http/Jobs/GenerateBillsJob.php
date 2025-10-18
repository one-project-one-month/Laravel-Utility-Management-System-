<?php

namespace App\Http\Jobs;

use App\Models\Room;
use App\Models\User;
use Illuminate\Log\Logger;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use App\Http\Services\Api\BillingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

Class GenerateBillsJob implements ShouldQueue {
    use Dispatchable, Queueable;

    public function __construct() {
    }

    public function handle(BillingService $billingService) {
         User::with('tenant')
        ->where('role', 'Tenant')
        ->chunk(100, function ($users) use ($billingService) {
            foreach ($users as $user) {
                $number =  fake()->randomNumber(8, true);
                $customInvoice = "INV".'-'.$number;
                $freshUser = User::with('tenant')->find($user->id);
                $billingService->generateBillForRoom($freshUser,$customInvoice);
            }
        });
     }
}

