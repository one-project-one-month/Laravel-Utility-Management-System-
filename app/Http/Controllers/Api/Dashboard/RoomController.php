<?php

namespace App\Http\Controllers\Api\Dashboard;

use Exceptiion;
use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Dashboard\RoomResource;
use Exception;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    use ApiResponse;

    /**
     * Create a new room
     */
    // public function create(Request $request){

    //     // check validation room data input
    //     $validator = Validator::make($request->all(), [
    //         'roomNo' => 'required|integer',
    //         'dimension' => 'required|string|max:255',
    //         'noOfBedRoom' => 'required|integer|min:1|max:4',
    //         'status' => 'required|in:Available,Rented,Purchased,In Maintenance',
    //         'sellingPrice' => 'required|numeric|regex:/^\d{1,18}(\.\d{1,2})?$/',
    //         'maxNoOfPeople' => 'required|integer|min:1|max:6',
    //         'description' => 'string|max:255'
    //     ]);

    //     // return error if validation fails
    //     if ($validator->fails()) {
    //         return $this->errorResponse($validator->errors(),422);
    //     }

    //     // error handling state
    //     try {

    //         // create new room
    //         $roomData = Room::create([
    //             'room_no' => $request->roomNo,
    //             'dimension' => $request->dimension,
    //             'no_of_bed_room' => $request->noOfBedRoom,
    //             'status' => $request->status,
    //             'selling_price' => $request->sellingPrice,
    //             'max_no_of_people' => $request->maxNoOfPeople,
    //             'description' => $request->description
    //         ]);

    //         // success response
    //         return $this->successResponse('New room created successfully',new RoomResource($roomData),200);

    //     } catch(\Exception $error) {
    //         // error response
    //         return $this->errorResponse('New room creation fails: ' . $error->getMessage(), 500);
    //     }

    // }

    public function index(){

        // retrieve a list of rooms with pagination
        $roomData = Room::paginate(10);

        // return list of rooms
        return $this->successResponse('Rooms retrieved successfully', RoomResource::collection($roomData),200);
    }

    public function show(String $id){

        if (!Str::isUuid($id)) {
            return $this->errorResponse('Invalid room id format', 400);
        }

        $roomData = Room::find($id);

        if(!$roomData ){
            return $this->errorResponse('Room not found',404);
        }

        return $this->successResponse('Room retrieved successfully', new RoomResource($roomData), 200);
    }

    public function update(Request $request, String $id){

        if (!Str::isUuid($id)) {
            return $this->errorResponse('Invalid room id format', 400);
        }

        $validator = Validator::make($request->all(), [
            'roomNo' => 'required|integer|unique:rooms,room_no,' . $id . ',id',
            'dimension' => 'required|string|max:255',
            'noOfBedRoom' => 'required|integer|min:1|max:4',
            'status' => 'required|in:Available,Rented,Purchased,In Maintenance',
            'sellingPrice' => 'required|numeric|regex:/^\d{1,18}(\.\d{1,2})?$/',
            'maxNoOfPeople' => 'required|integer|min:1|max:6',
            'description' => 'string|max:255'
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
                'dimension' => $request->dimension,
                'no_of_bed_room' => $request->noOfBedRoom,
                'status' => $request->status,
                'selling_price' => $request->sellingPrice,
                'max_no_of_people' => $request->maxNoOfPeople,
                'description' => $request->description
            ]);

            return $this->successResponse('Room updated successfully',new RoomResource($roomData),200);

        } catch (\Exception $error) {
            //error response
            return $this->errorResponse('Room update fails: '.$error->getMessage(), 500);
        }
    }
}
