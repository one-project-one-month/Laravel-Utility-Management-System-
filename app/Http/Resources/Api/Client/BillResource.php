<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
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
            'totalUnit' => new TotalUnitResource($this->whenLoaded('totalUnit')),
        ];
    }
}
