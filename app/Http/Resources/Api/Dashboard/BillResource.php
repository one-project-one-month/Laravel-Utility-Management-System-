<?php

namespace App\Http\Resources\Api\Dashboard;

use App\Models\TotalUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Dashboard\TotalUnitResource;



/**
 * @OA\Schema(
 * schema="BillResource",
 * title="Bill Resource",
 * description="Bill model representation",
 * @OA\Property(property="id", type="integer", description="Bill's unique identifier"),
 * @OA\Property(property="roomId", type="string", format="uuid", description="Associated room's UUID"),
 * @OA\Property(property="rentalFee", type="number", format="float", description="Monthly rental fee"),
 * @OA\Property(property="electricityFee", type="number", format="float", description="Electricity consumption fee"),
 * @OA\Property(property="waterFee", type="number", format="float", description="Water consumption fee"),
 * @OA\Property(property="fineFee", type="number", format="float", nullable=true, description="Any applicable late fees or fines"),
 * @OA\Property(property="serviceFee", type="number", format="float", description="General service charges"),
 * @OA\Property(property="groundFee", type="number", format="float", description="Ground maintenance fees"),
 * @OA\Property(property="carParkingFee", type="number", format="float", nullable=true, description="Car parking charges"),
 * @OA\Property(property="wifiFee", type="number", format="float", nullable=true, description="Wi-Fi service charges"),
 * @OA\Property(property="totalAmount", type="number", format="float", description="The total bill amount"),
 * @OA\Property(property="dueDate", type="string", format="date", description="Payment due date")
 * )
 */
class BillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'roomId' => $this->room_id,
            'rentalFee' => $this->rental_fee,
            'electricityFee' => $this->electricity_fee,
            'waterFee' => $this->water_fee,
            'fineFee' => $this->fine_fee,
            'serviceFee' => $this->service_fee,
            'groundFee' => $this->ground_fee,
            'carParkingFee' => $this->car_parking_fee,
            'wifiFee' => $this->wifi_fee,
            'totalAmount' => $this->total_amount,
            'dueDate' => $this->due_date,
            // 'totalUnit' => $this->totalUnit,
            // 'invoice' => $this->invoice,
            'totalUnit' => new TotalUnitResource($this->whenLoaded('totalUnit')),
            'invoice'  => new InvoiceResource($this->whenLoaded('invoice')),
            'tenant' => new TenantResource($this->whenLoaded('tenant')),
            'room'  => new RoomResource($this->whenLoaded('room')),
        ];
    }
}
