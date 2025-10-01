<?php
namespace app\Http\Resources\Api\Dashboard;


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
        return
            [
                'id'            => $this->id,
                'roomId'        => $this->room_id,
                'name'          => $this->names,
                'nrc'           => $this->nrcs,
                'email'         => $this->emails,
                'phNumber'      => $this->phone_nos,
                'emergencyNo'   => $this->emergency_nos,
            ];
    }
}
