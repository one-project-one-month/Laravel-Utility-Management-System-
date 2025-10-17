<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *   schema="TotalUnitResource",
 *   title="Total Unit Resource",
 *   description="Total unit model representation",
 *   @OA\Property(property="id", type="integer", description="Record ID", example=1),
 *   @OA\Property(property="billId", type="integer", description="ID of the associated bill", example=1),
 *   @OA\Property(property="electricityUnits", type="integer", description="Total electricity units consumed", example=150),
 *   @OA\Property(property="waterUnits", type="integer", description="Total water units consumed", example=50),
 *   @OA\Property(property="tenantName", type="string", description="Tenant name", example="Aung Aung"),
 *   @OA\Property(property="roomNo", type="string", description="Room number", example="101")
 * )
 */
class TotalUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'billId'           => $this->bill_id,
            'electricityUnits' => $this->electricity_units,
            'waterUnits'       => $this->water_units,
            'tenantName'       => $this->bill?->tenant?->name,
            'roomNo'           => $this->bill?->room?->room_no,
        ];
    }
}
