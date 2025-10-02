<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'billId' => $this->bill_id,
            'status' => $this->status,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,

        //Bill info
        'bill' => $this->whenLoaded('bill', function () {
            return [
                'id'              => $this->bill->id,
                'room_id'         => $this->bill->room_id,
                'user_id'         => $this->bill->user_id,
                'rental_fee'      => $this->bill->rental_fee,
                'electricity_fee' => $this->bill->electricity_fee,
                'water_fee'       => $this->bill->water_fee,
                'fine_fee'        => $this->bill->fine_fee,
                'service_fee'     => $this->bill->service_fee,
                'ground_fee'      => $this->bill->ground_fee,
                'car_parking_fee' => $this->bill->car_parking_fee,
                'wifi_fee'        => $this->bill->wifi_fee,
                'total_amount'    => $this->bill->total_amount,
                'due_date'        => $this->bill->due_date,
                'createdAt'       => $this->bill->created_at,
                'updatedAt'       => $this->bill->updated_at,
            ];
        }),
        ];
    }
}





?>