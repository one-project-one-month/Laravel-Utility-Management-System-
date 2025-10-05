<?php

namespace App\Http\Resources\Api\Dashboard;

use App\Http\Helpers\PostgresHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;



/**
 * @OA\Schema(
 * schema="ContractTypeResource",
 * title="Contract Type Resource",
 * description="Contract Type model representation",
 * @OA\Property(property="id", type="integer", example=1),
 * @OA\Property(property="name", type="string", example="Standard 1-Year"),
 * @OA\Property(property="duration", type="integer", description="Duration in months", example=12),
 * @OA\Property(property="price", type="number", format="float", example=500.00),
 * @OA\Property(
 * property="facilities",
 * type="array",
 * @OA\Items(type="string"),
 * example={"WiFi", "Air Conditioning"}
 * )
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
            'id'   => $this->id,
            'name' => $this->name,
            'duration' => $this->duration,
            'price' => $this->price,
            'facilities' => $this->pgArrayStringToNativeArray($this->facilities)
        ];
    }
}
