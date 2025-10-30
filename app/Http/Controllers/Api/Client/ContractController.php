<?php

namespace App\Http\Controllers\Api\Client;

//use App\Models\User;
use App\Models\Contract;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\ContractResource;

class ContractController extends Controller
{
    use ApiResponse;
    /**
     * @OA\Get(
     *     path="/api/v1/tenants/{id}/contracts",
     *     summary="Get contract details for a tenant",
     *     description="Retrieves the contract details associated with a specific tenant.",
     *     tags={"Client"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the tenant",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contract retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example="true"),
     *             @OA\Property(property="message", type="string", example="Contract retrieved successfully."),
     *             @OA\Property(property="content", ref="#/components/schemas/ClientContractResource"),
     *             @OA\Property(property="status", type="integer", example="200")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Contract not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example="false"),
     *             @OA\Property(property="message", type="string", example="Contract not found"),
     *             @OA\Property(property="status", type="integer", example="404")
     *         )
     *     )
     * )
     */
    public function index($tenantId){
        // Authorize
//        $userId = User::where('tenant_id', $tenantId)->pluck('id')->first();
//        if (auth('sanctum')->user()->id != $userId) {
//            return $this->errorResponse('Unathorized', 401);
//        }

        $contract = Contract::with(['contractType', 'tenant', 'room'])
                    ->where('tenant_id' , $tenantId)
                    ->paginate(config('pagination.perPage'));
        if (!$contract) {
            return $this->errorResponse('Contract not found.', 404);
        }
        return $this->successResponse('Contract retrieved successfully.',$this->buildPaginatedResourceResponse(ContractResource::class, $contract));
    }
}
