<?php
namespace App\Http\Resources\Api\Dashboard;


use App\Http\Helpers\PostgresHelper;
use Illuminate\Http\Request;
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
        return
            [
                'id'            => $this->id,
                'roomId'        => $this->room_id,
                'name'          => $this->pgArrayStringToNativeArray($this->names),
                'nrc'           => $this->pgArrayStringToNativeArray($this->nrcs),
                'email'         => $this->pgArrayStringToNativeArray($this->emails),
                'phNumber'      => $this->pgArrayStringToNativeArray($this->phone_nos),
                'emergencyNo'   => $this->pgArrayStringToNativeArray($this->emergency_nos),
            ];
    }
}
