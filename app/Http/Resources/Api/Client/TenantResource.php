<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ClientTenantResource",
 *     title="Client Tenant Resource",
 *     description="Client-facing tenant model representation",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="nrc",
 *         type="string",
 *         example="12/ABC(N)123456"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         example="john.doe@example.com"
 *     ),
 *     @OA\Property(
 *         property="phNumber",
 *         type="string",
 *         example="09123456789"
 *     ),
 *     @OA\Property(
 *         property="emergencyNo",
 *         type="string",
 *         example="09111222333"
 *     )
 * )
 */
class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'roomId'       =>$this->room_id,
            'name'         => $this->name,
            'nrc'          => $this->nrc,
            'email'        => $this->email,
            'phNumber'     => $this->phone_no,
            'emergencyNo'  => $this->emergency_no,
        ];
    }
}
