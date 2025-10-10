<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 * schema="ReceiptResource",
 * title="Receipt Resource",
 * description="Receipt model representation",
 * @OA\Property(property="id", type="integer", description="Receipt ID", example=1),
 * @OA\Property(property="invoiceId", type="string", format="uuid", description="ID of the associated invoice", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
 * @OA\Property(property="paymentMethod", type="string", description="Method of payment", example="Cash"),
 * @OA\Property(property="paidDate", type="string", format="date", description="The date the receipt was paid", example="2023-10-28")
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
            'paidDate' => $this->paid_date
        ];
    }
}
