<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ContractTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'duration' => $this->duration,
            'price' => $this->price,
            'facilities' => $this->facilities
        ];
    }

    private function textArrayToString(String $textArray): String
    {
        return str_replace(
            "\"",
            "",
            Str::between($textArray, '{', '}')
        );
    }
}
