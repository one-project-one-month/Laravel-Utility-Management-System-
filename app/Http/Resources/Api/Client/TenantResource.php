<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Request;
use App\Http\Helpers\PostgresHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
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
            'names' => $this->pgArrayStringToNativeArray($this->names),
            'nrcs' => $this->pgArrayStringToNativeArray($this->nrcs),
            'emails' => $this->pgArrayStringToNativeArray($this->emails),
            'phone_nos' => $this->pgArrayStringToNativeArray($this->phone_nos),
            'emergency_nos' => $this->pgArrayStringToNativeArray($this->emergency_nos),
        ];
    }
}
