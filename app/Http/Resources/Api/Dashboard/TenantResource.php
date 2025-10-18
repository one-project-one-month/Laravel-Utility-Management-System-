<?php
namespace App\Http\Resources\Api\Dashboard;


use Illuminate\Http\Request;
use App\Http\Helpers\PostgresHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Dashboard\OccupantResource;



/**
 * @OA\Schema(
 *   schema="TenantResource",
 *   title="Tenant Resource",
 *   description="Tenant model representation",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="roomId", type="string", format="uuid", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
 *   @OA\Property(
 *     property="name",
 *     type="array",
 *     @OA\Items(type="string"),
 *     example={"John Doe", "Jane Doe"}
 *   ),
 *   @OA\Property(
 *     property="nrc",
 *     type="array",
 *     @OA\Items(type="string"),
 *     example={"12/ABC(N)123456", "12/DEF(N)654321"}
 *   ),
 *   @OA\Property(
 *     property="email",
 *     type="array",
 *     @OA\Items(type="string", format="email"),
 *     example={"john.doe@example.com", "jane.doe@example.com"}
 *   ),
 *   @OA\Property(
 *     property="phNumber",
 *     type="array",
 *     @OA\Items(type="string"),
 *     example={"09123456789", "09987654321"}
 *   ),
 *   @OA\Property(
 *     property="emergencyNo",
 *     type="array",
 *     @OA\Items(type="string"),
 *     example={"09111222333", "09444555666"}
 *   ),
 *   @OA\Property(
 *     property="occupants",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/OccupantResource")
 *   )
 * )
 */
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
                'name'          => $this->name,
                'nrc'           => $this->nrc,
                'email'         => $this->email,
                'phNumber'      => $this->phone_no,
                'emergencyNo'   => $this->emergency_no,
                'occupants'     => OccupantResource::collection($this->whenLoaded('occupants')),
                'contracts'     => ContractResource::collection($this->whenLoaded('contracts')),
            ];
    }
}
