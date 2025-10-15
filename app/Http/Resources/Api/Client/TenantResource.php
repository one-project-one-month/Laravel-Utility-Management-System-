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
            'phone_no'     => $this->phone_no,
            'emergency_no' => $this->emergency_no,
        ];
    }
}
