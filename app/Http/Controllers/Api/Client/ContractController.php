<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\User;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\ContractResource;

class ContractController extends Controller
{
    use ApiResponse;
    public function index($tenantId){
        // Authorize
        $userId = User::where('tenant_id', $tenantId)->pluck('id')->first();
        if (auth('sanctum')->user()->id != $userId) {
            return $this->errorResponse('Unathorized', 401);
        }
        
        $contract = Contract::with(['contractType', 'tenant'])->where('tenant_id' , $tenantId)->first();
        if (!$contract) {
            return $this->errorResponse('Contract not found.', 404);
        }
        return $this->successResponse('Contract retrieved successfully.',new ContractResource($contract), 200);
    }
}
