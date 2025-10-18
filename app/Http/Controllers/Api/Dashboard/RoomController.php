<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Dashboard\RoomResource;


/**
 * @OA\Tag(
 * name="Rooms",
 * description="API Endpoints for managing rooms"
 * )
 */
class RoomController extends Controller
{
    use ApiResponse;


     /**
     * @OA\Get(
     * path="/api/v1/rooms",
     * summary="Get a list of rooms",
     * description="Returns a paginated list of all rooms.",
     * tags={"Rooms"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Rooms retrieved successfully"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/RoomResource")),
     * @OA\Property(property="pagination", type="object",
     * @OA\Property(property="total", type="integer", example=50),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=4)
     * )
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Rooms not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(){

        // retrieve a list of rooms with pagination
        $roomData = Room::paginate(config('pagination.perPage'));

        if ($roomData->isEmpty()) {
            return $this->errorResponse('Rooms not found', 404);
        }

        // return list of rooms
        return $this->successResponse(
            'Rooms retrieved successfully',
            $this->buildPaginatedResourceResponse(RoomResource::class, $roomData)
        );
    }


     /**
     * @OA\Get(
     * path="/api/v1/rooms/{id}",
     * summary="Get a single room",
     * description="Returns the details of a specific room by its UUID.",
     * tags={"Rooms"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="UUID of the room",
     * @OA\Schema(type="string", format="uuid")
     * ),
     * @OA\Response(
     * response=200,
     * description="Room retrieved successfully",
     * @OA\JsonContent(ref="#/components/schemas/RoomResource")
     * ),
     * @OA\Response(response=404, description="Room not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show(String $id){

        if (!Str::isUuid($id)) {
            return $this->errorResponse('Invalid room id format');
        }

        $roomData = Room::find($id);

        if(!$roomData ){
            return $this->errorResponse('Room not found',404);
        }

        return $this->successResponse('Room retrieved successfully', new RoomResource($roomData));
    }


     /**
     * @OA\Put(
     * path="/api/v1/rooms/{id}",
     * summary="Update an existing room",
     * description="Updates the details of an existing room.",
     * tags={"Rooms"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="UUID of the room to update",
     * @OA\Schema(type="string", format="uuid")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"roomNo", "floor", "dimension", "noOfBedRoom", "status", "sellingPrice", "maxNoOfPeople"},
     * @OA\Property(property="roomNo", type="integer", example=101),
     * @OA\Property(property="floor", type="integer", example=1),
     * @OA\Property(property="dimension", type="string", example="12x12 sqft"),
     * @OA\Property(property="noOfBedRoom", type="integer", example=1),
     * @OA\Property(property="status", type="string", enum={"Available", "Rented", "Purchased", "In Maintenance"}, example="Available"),
     * @OA\Property(property="sellingPrice", type="number", format="float", example=150000.00),
     * @OA\Property(property="maxNoOfPeople", type="integer", example=2),
     * @OA\Property(property="description", type="string", nullable=true, example="A cozy room with a nice view.")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Room updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/RoomResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=404, description="Room not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function update(Request $request, String $id){

        if (!Str::isUuid($id)) {
            return $this->errorResponse('Invalid room id format');
        }

        $validator = Validator::make($request->all(), [
            'roomNo' => 'required|integer|unique:rooms,room_no,' . $id . ',id',
            'floor' => 'required|integer|min:1|max:10',
            'dimension' => 'required|string|max:255',
            'noOfBedRoom' => 'required|integer|min:1|max:4',
            'status' => 'required|in:Available,Rented,Purchased,In Maintenance',
            'sellingPrice' => 'required|numeric|regex:/^\d{1,18}(\.\d{1,2})?$/',
            'maxNoOfPeople' => 'required|integer|min:1|max:6',
            'description' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(),422);
        }

        try {
            $roomData = Room::find($id);

            if( !$roomData ){
                return $this->errorResponse('Room not found',404);
            }

            $roomData->update([
                'room_no' => $request->roomNo,
                'floor' => $request->floor,
                'dimension' => $request->dimension,
                'no_of_bed_room' => $request->noOfBedRoom,
                'status' => $request->status,
                'selling_price' => $request->sellingPrice,
                'max_no_of_people' => $request->maxNoOfPeople,
                'description' => $request->description
            ]);

            return $this->successResponse('Room updated successfully',new RoomResource($roomData));

        } catch (\Exception $error) {
            //error response
            return $this->errorResponse('Room update fails: '.$error->getMessage(), 500);
        }
    }

    /**
     * Create a new room
     */
    public function store(Request $request){

        // check validation room data input
        $validator = Validator::make($request->all(), [
            'roomNo' => 'required|integer|unique:rooms,room_no',
            'floor' => 'required|integer|min:1|max:10',
            'dimension' => 'required|string|max:255',
            'noOfBedRoom' => 'required|integer|min:1|max:4',
            'status' => 'required|in:Available,Rented,Purchased,In Maintenance',
            'sellingPrice' => 'required|numeric|regex:/^\d{1,18}(\.\d{1,2})?$/',
            'maxNoOfPeople' => 'required|integer|min:1|max:6',
            'description' => 'string|max:255'
        ]);

        // return error if validation fails
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(),422);
        }

        // error handling state
        try {

            // create new room
            $roomData = Room::create([
                'room_no' => $request->roomNo,
                'floor' => $request->floor,
                'dimension' => $request->dimension,
                'no_of_bed_room' => $request->noOfBedRoom,
                'status' => $request->status,
                'selling_price' => $request->sellingPrice,
                'max_no_of_people' => $request->maxNoOfPeople,
                'description' => $request->description
            ]);

            // success response
            return $this->successResponse('New room created successfully',new RoomResource($roomData),200);

        } catch(\Exception $error) {
            // error response
            return $this->errorResponse('New room creation fails: ' . $error->getMessage(), 500);
        }

    }
}
