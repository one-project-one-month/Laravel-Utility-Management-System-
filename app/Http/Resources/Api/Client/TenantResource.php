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
            'name'         => $this->name,
            'nrc'          => $this->nrc,
            'email'        => $this->email,
            'phNumber'     => $this->phone_no,
            'emergencyNo'  => $this->emergency_no,
        ];
    }
}
