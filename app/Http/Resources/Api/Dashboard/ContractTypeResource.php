<?php

namespace App\Http\Resources\Api\Dashboard;

use App\Http\Helpers\PostgresHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
