<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Dashboard\BillResource;




/**
 * @OA\Schema(
 * schema="ClientReceiptResource",
 * title="Client Receipt Resource",
 * description="Client-facing receipt model representation",
 * @OA\Property(property="id", type="integer", description="Receipt ID", example=1),
 * @OA\Property(property="invoiceId", type="integer", description="Associated Invoice ID", example=101),
 * @OA\Property(property="paymentMethod", type="string", enum={"Cash", "Bank Transfer", "Credit Card"}, description="Payment method used", example="Credit Card"),
 * @OA\Property(property="paidDate", type="string", format="date", description="Date of payment", example="2023-10-28")
 * )
 */
class ReceiptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoiceId' => $this->invoice_id,
            'paymentMethod' => $this->payment_method,
            'paidDate' => $this->paid_date,
            'bill' => new BillResource($this->invoice->bill),
        ];
    }
}
