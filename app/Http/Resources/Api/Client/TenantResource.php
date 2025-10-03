<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'names' => $this->names,
            'nrcs' => $this->nrcs,
            'emails' => $this->emails,
            'phone_nos' => $this->phone_nos,
            'emergency_nos' => $this->emergency_nos,
        ];
    }
}
