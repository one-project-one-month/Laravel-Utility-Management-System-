<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



/**
 * @OA\Schema(
 * schema="CustomerServiceResource",
 * title="Customer Service Resource",
 * description="Customer Service model representation",
 * @OA\Property(property="id", type="integer", description="Service request ID", example=1),
 * @OA\Property(property="roomId", type="string", format="uuid", description="ID of the associated room", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
 * @OA\Property(property="category", type="string", description="Category of the service request", example="Maintenance"),
 * @OA\Property(property="description", type="string", description="Detailed description of the issue", example="Leaky faucet in the kitchen."),
 * @OA\Property(property="status", type="string", description="Current status of the request", example="Ongoing"),
 * @OA\Property(property="priorityLevel", type="string", description="Priority level of the request", example="Medium"),
 * @OA\Property(property="issuedDate", type="string", format="date", description="Date the request was issued", example="2025-10-05")
 * )
 */
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
            'issuedDate'    => $this->issued_date
        ];
    }
}
