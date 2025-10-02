<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'roomId'        => $this->room_id,
            'category'      => $this->category,
            'description'   => $this->description,
            'status'        => $this->status,
            'priorityLevel' => $this->priority_level,
            'issuedDate'    => $this->issued_date?->format('Y-m-d'),
        ];
    }
}
