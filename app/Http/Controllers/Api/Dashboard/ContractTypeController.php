<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\ContractTypeResource;
use App\Models\ContractType;

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

    /**
     * Display a specific contract type.
     */
    public function show(String $id)
    {
        // retrieve a single resource
        $contractType = ContractType::find($id);

        // return error if the resource is not found
        if (!$contractType) {
            return $this->errorResponse(
                'Contract type not found',
                404
            );
        }

        // return the resource
        return $this->successResponse(
            'Contract type retrieved successfully',
            new ContractTypeResource($contractType),
            200
        );
    }
}
