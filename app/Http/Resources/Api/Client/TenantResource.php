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
            'name'         => $this->name,
            'nrc'          => $this->nrc,
            'email'        => $this->email,
            'phone_no'     => $this->phone_no,
            'emergency_no' => $this->emergency_no,
        ];
    }
}
