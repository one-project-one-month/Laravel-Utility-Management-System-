<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'roomNo' => $this->room_no,
            'dimension' => $this->dimension,
            'noOfBedRoom' => $this->no_of_bed_room,
            'status' => $this->status,
            'sellingPrice' => $this->selling_price,
            'maxNoOfPeople' => $this->max_no_of_people,
            'description' => $this->description,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
