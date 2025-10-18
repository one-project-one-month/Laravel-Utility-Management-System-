<?php

namespace App\Http\Controllers\Api\Client;

//use App\Models\User;
use App\Models\Contract;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\ContractResource;



/**
 * @OA\Tag(
 * name="Client Contracts",
 * description="Endpoints for tenants to view their contract information"
 * )
 */
class ContractController extends Controller
{
    use ApiResponse;
    /**
     * @OA\Get(
     * path="/api/tenants/{id}/contracts",
     * summary="Get contract details for a tenant",
     * description="Retrieves the contract details associated with a specific tenant.",
     * tags={"Client Contracts"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="The ID of the tenant",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="Contract retrieved successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Contract retrieved successfully."),
     * @OA\Property(property="data", ref="#/components/schemas/ClientContractResource")
     * )
     * ),
     * @OA\Response(response=404, description="Contract not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index($tenantId){
        // Authorize
//        $userId = User::where('tenant_id', $tenantId)->pluck('id')->first();
//        if (auth('sanctum')->user()->id != $userId) {
//            return $this->errorResponse('Unathorized', 401);
//        }

        $contract = Contract::with(['contractType', 'tenant'])->where('tenant_id' , $tenantId)->first();
        if (!$contract) {
            return $this->errorResponse('Contract not found.', 404);
        }

        return $this->successResponse('Contract retrieved successfully.',new ContractResource($contract), 200);
    }
}
