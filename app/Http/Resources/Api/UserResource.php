<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\TenantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'userName' => $this->user_name,
            'email' => $this->email,
            'role' => $this->role,
            'isActive' => $this->is_active,
            'tenantId' => $this->tenant_id,
            'tenant' => new TenantResource($this->whenLoaded('tenant')),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
