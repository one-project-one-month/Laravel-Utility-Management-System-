<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'bill'      => [
                'id'                => $this->bill->id,
                'roomId'            => $this->bill->room_id,
                'rentalFee'         => $this->bill->rental_fee,
                'electricityFee'    => $this->bill->electricity_fee,
                'waterFee'          => $this->bill->water_fee,
                'fineFee'           => $this->bill->fine_fee,
                'serviceFee'        => $this->bill->service_fee,
                'groundFee'         => $this->bill->ground_fee,
                'carParkingFee'     => $this->bill->car_parking_fee,
                'wifiFee'           => $this->bill->wifi_fee,
                'totalAmount'       => $this->bill->total_amount,
                'dueDate'           => $this->bill->due_date,
                'totalUnit'         => [
                    'electricityUnits'  => $this->bill->totalUnit->electricity_units,
                    'waterUnits'       => $this->bill->totalUnit->water_units,
                ]
            ]
        ];
    }
}
