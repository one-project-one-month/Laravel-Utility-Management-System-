<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'expiryDate' => $this->expiry_date
            ];
    }
}
