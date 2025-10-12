<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\Occupant;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Dashboard\OccupantResource;

class OccupantController extends Controller
{
    use  ApiResponse ;
    
    public function index() {
        $occupants = Occupant::get();
        return $this->successResponse('Occupants retrieved successfully',OccupantResource::collection($occupants));
    }
}
