<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Dashboard\RoomResource;

class RoomController extends Controller
{
    use ApiResponse;

    public function index(){

        // retrieve a list of rooms with pagination
        $roomData = Room::orderBy('created_at', 'desc')
            ->paginate(config('pagination.perPage'));

        if ($roomData->isEmpty()) {
            return $this->errorResponse('Rooms not found', 404);
        }

        // return list of rooms
        return $this->successResponse(
            'Rooms retrieved successfully',
            $this->buildPaginatedResourceResponse(RoomResource::class, $roomData)
        );
    }

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
}
