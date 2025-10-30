<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Dashboard\RoomResource;
use App\Http\Resources\Api\Dashboard\TenantResource;
use App\Http\Resources\Api\Dashboard\TotalUnitResource;

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
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'totalUnit' => new TotalUnitResource($this->whenLoaded('totalUnit')),
            'tenant' => new TenantResource($this->whenLoaded('tenant')),
            'room'  => new RoomResource($this->whenLoaded('room')),
            'invoice' => [
                'id'        => $this->invoice->id,
                'status'    => $this->invoice->status,
            ],
            'receipt' => [
                'id'        => $this->invoice->receipt->id,
                'paidDate' => $this->invoice->receipt->paid_date,
                'paymentMethod' => $this->invoice->receipt->payment_method
            ]
        ];
    }
}

