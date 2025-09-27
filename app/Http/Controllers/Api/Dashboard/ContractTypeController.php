<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\ContractTypeResource;
use App\Models\ContractType;
use Illuminate\Http\Request;

class ContractTypeController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of contract types.
     */
    public function index()
    {
        $contractTypes = ContractType::paginate(10);

        return $this->successResponse(
            'Contract types retrieved successfully',
            ContractTypeResource::collection($contractTypes),
            200
        );
    }
}
