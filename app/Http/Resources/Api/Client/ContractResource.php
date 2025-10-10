<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 * schema="ClientContractResource",
 * title="Client Contract Resource",
 * description="Client-facing contract model representation",
 * @OA\Property(property="id", type="string", format="uuid", description="Contract ID", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
 * @OA\Property(property="room_id", type="string", format="uuid", description="Associated Room ID", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
 * @OA\Property(property="expiry_date", type="string", format="date", description="Expiry date of the contract", example="2025-10-27"),
 * @OA\Property(property="tenant", ref="#/components/schemas/TenantResource"),
 * @OA\Property(property="contractType", ref="#/components/schemas/ContractTypeResource")
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
            'room_id' => $this->room_id,
            'tenant_id' => $this->tenant_id,
            'tenant' => new TenantResource($this->whenLoaded('tenant')),
            'expiry_date' => $this->expiry_date,
            'contractType' => new ContractTypeResource($this->whenLoaded('contractType'))
        ];
    }
}
