<?php

namespace App\Http\Jobs;


use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Services\Api\ReceiptService;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendReceiptEmailJob implements ShouldQueue
{
     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $receiptId;

    public function __construct($receiptId)
    {
        $this->receiptId= $receiptId;
    }

    /**
     * Execute the job.
     */
    public function handle(ReceiptService $receiptService): void
    {
        $receiptService->sentReceipt($this->receiptId);
    }
}
