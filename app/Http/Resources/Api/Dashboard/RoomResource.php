<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



/**
 * @OA\Schema(
 * schema="RoomResource",
 * title="Room Resource",
 * description="Room model representation",
 * @OA\Property(property="id", type="string", format="uuid", description="Room's unique identifier"),
 * @OA\Property(property="roomNo", type="integer", description="Room number", example=101),
 * @OA\Property(property="floor", type="integer", description="Floor number", example=1),
 * @OA\Property(property="dimension", type="string", description="Room dimensions", example="12x12 sqft"),
 * @OA\Property(property="noOfBedRoom", type="integer", description="Number of bedrooms", example=1),
 * @OA\Property(property="status", type="string", description="Current status of the room", enum={"Available", "Rented", "Purchased", "In Maintenance"}, example="Available"),
 * @OA\Property(property="sellingPrice", type="number", format="float", description="Selling price of the room", example=150000.00),
 * @OA\Property(property="maxNoOfPeople", type="integer", description="Maximum number of occupants", example=2),
 * @OA\Property(property="description", type="string", nullable=true, description="A brief description of the room", example="A cozy room with a nice view."),
 * @OA\Property(property="createdAt", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updatedAt", type="string", format="date-time", description="Last update timestamp")
 * )
 */
class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'roomNo' => $this->room_no,
            'floor' => $this->floor,
            'dimension' => $this->dimension,
            'noOfBedRoom' => $this->no_of_bed_room,
            'status' => $this->status,
            'sellingPrice' => $this->selling_price,
            'maxNoOfPeople' => $this->max_no_of_people,
            'description' => $this->description,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
