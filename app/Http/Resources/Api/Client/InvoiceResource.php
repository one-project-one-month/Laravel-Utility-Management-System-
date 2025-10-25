<?php

namespace App\Http\Resources\Api\Client;

use App\Models\TotalUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ClientInvoiceResource",
 *     title="Client Invoice Resource",
 *     description="Invoice model representation",
 *     @OA\Property(property="id", type="integer", description="Invoice ID", example=1),
 *     @OA\Property(property="status", type="string", description="Status of the invoice", example="Paid"),
 *     @OA\Property(
 *         property="bill",
 *         type="object",
 *         description="Associated bill information",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="roomId", type="string", format="uuid", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
 *         @OA\Property(property="userId", type="integer", example=1),
 *         @OA\Property(property="rentalFee", type="string", format="float", example="500.00"),
 *         @OA\Property(property="electricityFee", type="string", format="float", example="50.00"),
 *         @OA\Property(property="waterFee", type="string", format="float", example="25.00"),
 *         @OA\Property(property="fineFee", type="string", format="float", example="0.00"),
 *         @OA\Property(property="serviceFee", type="string", format="float", example="10.00"),
 *         @OA\Property(property="groundFee", type="string", format="float", example="5.00"),
 *         @OA\Property(property="carParkingFee", type="string", format="float", example="15.00"),
 *         @OA\Property(property="wifiFee", type="string", format="float", example="20.00"),
 *         @OA\Property(property="totalAmount", type="string", format="float", example="625.00"),
 *         @OA\Property(property="dueDate", type="string", format="date", example="2023-11-28"),
 *         @OA\Property(
 *             property="totalUnits",
 *             type="object",
 *             @OA\Property(property="electricityUnits", type="string", format="float", example="226.36"),
 *             @OA\Property(property="waterUnits", type="string", format="float", example="33.26")
 *         ),
 *     )
 * )
 */
class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'status'    => $this->status,
            'bill'      => new BillResource($this->whenLoaded('bill')),
        ];
    }
}
