<?php

namespace App\Http\Resources\Api\Client;

use App\Http\Helpers\PostgresHelper;
use Illuminate\Http\Request;
use App\Http\Helpers\PostgresHelper;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ClientContractTypeResource",
 *     title="Client Contract Resource",
 *     description="Client-facing contract model representation",
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="name", type="string", example="3 months"),
 *     @OA\Property(property="duration", type="integer", example="3"),
 *     @OA\Property(property="price", type="float", example="800000.00"),
 * )
 */
class ContractTypeResource extends JsonResource
{
    use PostgresHelper;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'duration' => $this->duration,
            'price' => $this->price,
            'facilities' => $this->pgArrayStringToNativeArray($this->facilities)
        ];
    }
}
