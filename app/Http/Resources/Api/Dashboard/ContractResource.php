<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * schema="ContractResource",
 * title="Contract Resource",
 * description="Contract model representation",
 * @OA\Property(property="id", type="integer", description="Contract ID", example=1),
 * @OA\Property(property="roomNo", type="string", format="uuid", description="ID of the associated room", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
 * @OA\Property(property="contractId", type="integer", description="ID of the contract type", example=1),
 * @OA\Property(property="tenantId", type="integer", description="ID of the associated tenant", example=1),
 * @OA\Property(property="createdDate", type="string", format="date-time", description="The date the contract was created", example="2023-10-27T10:00:00.000000Z"),
 * @OA\Property(property="expiryDate", type="string", format="date", description="The date the contract expires", example="2024-10-26"),
 * @OA\Property(property="contractType", ref="#/components/schemas/ContractTypeResource"),
 * @OA\Property(property="tenant", ref="#/components/schemas/TenantResource")
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
        return
            [
                'id' => $this->id,
                'roomNo' => $this->room_id,
                'contractId' => $this->contract_type_id,
                'tenantId' => $this->tenant_id,
                'createdDate' => $this->created_at,
                'expiryDate' => $this->expiry_date,
                'contractType' => new ContractTypeResource($this->whenLoaded('contractType')),
                'tenant' => new TenantResource($this->whenLoaded('tenant')),
            ];
    }
}
