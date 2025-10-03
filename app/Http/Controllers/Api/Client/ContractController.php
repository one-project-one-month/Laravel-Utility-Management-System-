<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Client\ContractResource;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContractController extends Controller
{
    use ApiResponse;
    public function index($id){
        $contract = Contract::with(['contractType', 'tenant'])->where('tenant_id' , $id)->first();
        if (!$contract) {
            return $this->errorResponse('Contract not found.', 404);
        }
        return $this->successResponse('Contract retrieved successfully.',new ContractResource($contract), 200);
    }
}
