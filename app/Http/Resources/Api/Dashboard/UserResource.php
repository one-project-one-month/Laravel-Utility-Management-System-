<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 * schema="UserResource",
 * title="User Resource",
 * description="User model representation",
 * @OA\Property(property="id", type="integer", description="User ID", example=1),
 * @OA\Property(property="userName", type="string", description="Name of the user", example="John Doe"),
 * @OA\Property(property="email", type="string", format="email", description="Email of the user", example="johndoe@example.com"),
 * @OA\Property(property="role", type="string", description="Role of the user", example="Tenant"),
 * @OA\Property(property="isActive", type="boolean", description="User's active status", example=true),
 * @OA\Property(property="tenantId", type="integer", description="ID of the associated tenant", example=1),
 * @OA\Property(property="tenant", ref="#/components/schemas/TenantResource"),
 * @OA\Property(property="createdAt", type="string", format="date-time", description="Creation timestamp", example="2023-10-28T12:00:00.000000Z"),
 * @OA\Property(property="updatedAt", type="string", format="date-time", description="Last update timestamp", example="2023-10-28T12:00:00.000000Z")
 * )
 */
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
