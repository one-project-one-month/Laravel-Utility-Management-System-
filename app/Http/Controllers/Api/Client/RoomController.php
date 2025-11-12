<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Dashboard\RoomResource;

class RoomController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function show($id)
    {
        $tenant = Tenant::with('room')->find($id);
        if (!$tenant) {
            return $this->errorResponse('Tenant not found', 404);
        }
        $room = $tenant->room;

        if (!$room) {
            return $this->errorResponse('Tenant is not assigned to any room', 404);
        }
        return $this->successResponse(
            'Room retrieved successfully',
            new RoomResource($room),
            200
        );
    }
}
