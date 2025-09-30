<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\RoomResource;
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


    /**
     * Display a list of room
     */
    public function index(){

        // retrieve a list of rooms with pagination
        $roomData = Room::paginate(10);

        // return list of rooms
        return $this->successResponse('Rooms retrieved successfully', RoomResource::collection($roomData),200);
    }


    /**
     * Display a specific room
     */
    public function show(String $id){

        // check room uuid format
        if (!Str::isUuid($id)) {
            return $this->errorResponse('Invalid room id format', 400);
        }

        // retrieve a specific room
        $roomData = Room::find($id);

        // return error response if room not found
        if( !$roomData ){
            return $this->errorResponse('Room not found',404);
        }

        // return specific room
        return $this->successResponse('Room retrieved successfully', new RoomResource($roomData), 200);
    }


    /**
     * Update a specific room
     */
    public function update(Request $request, String $id){

        // check room uuid format
        if (!Str::isUuid($id)) {
            return $this->errorResponse('Invalid room id format', 400);
        }

        // check validation room data input
        $validator = Validator::make($request->all(), [
            'roomNo' => 'required|integer',
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

        try {

            // retrieve a specific room
            $roomData = Room::find($id);

            // return error response if room not found
            if( !$roomData ){
                return $this->errorResponse('Room not found',404);
            }

            // update a specific room with room data input
            $roomData->update([
                'room_no' => $request->roomNo,
                'dimension' => $request->dimension,
                'no_of_bed_room' => $request->noOfBedRoom,
                'status' => $request->status,
                'selling_price' => $request->sellingPrice,
                'max_no_of_people' => $request->maxNoOfPeople,
                'description' => $request->description
            ]);

            // success response
            return $this->successResponse('Room updated successfully',new RoomResource($roomData),200);

        } catch (\Exception $error) {
            //error response
            return $this->errorResponse('Room update fails: '.$error->getMessage(), 500);
        }

    }

}
