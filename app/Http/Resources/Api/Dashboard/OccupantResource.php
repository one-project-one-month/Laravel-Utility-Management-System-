<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OccupantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    /**
 * @OA\Schema(
 *   schema="OccupantResource",
 *   title="Occupant Resource",
 *   description="Occupant model representation",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="John Doe"),
 *   @OA\Property(property="nrc", type="string", example="12/ABC(N)123456"),
 *   @OA\Property(property="relationshipToTenant", type="string", example="Child"),
 *   @OA\Property(property="tenantId", type="integer", example=1)
 * )
 */

    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'nrc'  => $this->nrc,
            'relationshipToTenant' => $this->relationship_to_tenant,
            'tenantId' => $this->tenant_id
        ];
    }
}
