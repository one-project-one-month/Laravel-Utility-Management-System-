<?php

namespace App\Http\Resources\Api\Client;

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
        return [
            'id' => $this->id,
            'room_id' => $this->room_id,
            'tenant' => new TenantResource($this->whenLoaded('tenant')),
            'expiry_date' => $this->expiry_date,
            'contractType' => new ContractTypeResource($this->whenLoaded('contractType'))
        ];
    }
}
