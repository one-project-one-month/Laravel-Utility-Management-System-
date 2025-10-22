<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



/**
 * @OA\Schema(
 * schema="InvoiceResource",
 * title="Invoice Resource",
 * description="Invoice model representation",
 * @OA\Property(property="id", type="integer", description="Invoice ID", example=1),
 * @OA\Property(property="billId", type="integer", description="ID of the associated bill", example=1),
 * @OA\Property(property="status", type="string", description="Status of the invoice", example="Paid"),
 * @OA\Property(property="createdAt", type="string", format="date-time", description="Creation timestamp", example="2023-10-28T12:00:00.000000Z"),
 * @OA\Property(property="updatedAt", type="string", format="date-time", description="Last update timestamp", example="2023-10-28T12:00:00.000000Z"),
 * @OA\Property(
 * property="bill",
 * type="object",
 * description="Associated bill information",
 * @OA\Property(property="id", type="integer", example=1),
 * @OA\Property(property="room_id", type="string", format="uuid", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
 * @OA\Property(property="user_id", type="integer", example=1),
 * @OA\Property(property="rental_fee", type="number", format="float", example=500.00),
 * @OA\Property(property="electricity_fee", type="number", format="float", example=50.00),
 * @OA\Property(property="water_fee", type="number", format="float", example=25.00),
 * @OA\Property(property="fine_fee", type="number", format="float", example=0.00),
 * @OA\Property(property="service_fee", type="number", format="float", example=10.00),
 * @OA\Property(property="ground_fee", type="number", format="float", example=5.00),
 * @OA\Property(property="car_parking_fee", type="number", format="float", example=15.00),
 * @OA\Property(property="wifi_fee", type="number", format="float", example=20.00),
 * @OA\Property(property="total_amount", type="number", format="float", example=625.00),
 * @OA\Property(property="due_date", type="string", format="date", example="2023-11-28"),
 * @OA\Property(property="createdAt", type="string", format="date-time", example="2023-10-28T12:00:00.000000Z"),
 * @OA\Property(property="updatedAt", type="string", format="date-time", example="2023-10-28T12:00:00.000000Z")
 * )
 * )
 */
class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array{
        return [
            'id' => $this->id,
            'invoiceNo' => $this->invoice_no,
            'billId' => $this->bill_id,
            'status' => $this->status,
            'receiptSent' => $this->receipt_sent,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,

        //Bill info
        'bill' => new BillResource($this->whenLoaded('bill')),
        ];
    }
}





?>
