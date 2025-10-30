<?php

namespace App\Http\Resources\Api\Client;

use App\Http\Resources\Api\Dashboard\RoomResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="ClientContractResource",
 *     title="Client Contract Resource",
 *     description="Client-facing contract model representation",
 *     @OA\Property(property="id", type="string", format="uuid", description="Contract ID", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
 *     @OA\Property(property="roomId", type="string", format="uuid", description="Associated Room ID", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
 *     @OA\Property(property="tenantId", type="integer", example="1"),
 *     @OA\Property(property="tenant", ref="#/components/schemas/ClientTenantResource"),
 *     @OA\Property(property="expiryDate", type="string", format="date", description="Expiry date of the contract", example="2025-10-27"),
 *     @OA\Property(property="contractType", ref="#/components/schemas/ClientContractTypeResource")
 * )
 */
class ContractResource extends JsonResource
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
            'tenantId' => $this->tenant_id,
            'createdDate' => $this->created_date,
            'expiryDate' => $this->expiry_date,
            'room' => new RoomResource($this->whenLoaded('room')),
            'tenant' => new TenantResource($this->whenLoaded('tenant')),
            'contractType' => new ContractTypeResource($this->whenLoaded('contractType'))
        ];
    }
}
