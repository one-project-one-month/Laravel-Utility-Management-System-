<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Bill;
//use App\Models\User;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Dashboard\BillResource;

class BillController extends Controller
{
    use ApiResponse;

     /**
     * @OA\Get(
     *    path="/api/v1/tenants/{id}/bills/latest",
     *    summary="Get the latest bill for a tenant",
     *    description="Retrieves the most recent bill for a specific tenant based on the user ID.",
     *    tags={"Client"},
     *    security={{"bearerAuth":{}}},
     *    @OA\Parameter(
     *        name="id",
     *        in="path",
     *        required=true,
     *        description="The User ID of the tenant",
     *        @OA\Schema(type="string")
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\JsonContent(
     *            @OA\Property(property="success", type="boolean", example="true"),
     *            @OA\Property(property="message", type="string", example="latestBill Success"),
     *            @OA\Property(property="content", ref="#/components/schemas/BillResource"),
     *            @OA\Property(property="status", type="integer", example="200")
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthenticated",
     *        @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Bill not found",
     *        @OA\JsonContent(
     *            @OA\Property(property="success", type="boolean", example="false"),
     *            @OA\Property(property="message", type="string", example="Bill not found"),
     *            @OA\Property(property="status", type="integer", example="404")
     *        )
     *    )
     * )
     */
  //bill_latest
    public function latestBill($tenantId)
    {
         // Authorize
//        $userId = User::where('tenant_id', $tenantId)->pluck('id')->first();
//        if (auth('sanctum')->user()->id != $userId) {
//            return $this->errorResponse('Unathorized', 401);
//        }

        $latestBill = Bill::where('tenant_id', $tenantId)
                        ->orderBy('created_at', 'desc')
                        ->first();

        return $this->successResponse("latestBill Success",new BillResource($latestBill), 200);
    }



     /**
     * @OA\Get(
     *     path="/api/v1/tenants/{id}/bills/history",
     *     summary="Get the bill history for a tenant",
     *     description="Retrieves the bill history for the current year for a specific tenant.",
     *     tags={"Client"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The User ID of the tenant",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example="true"),
     *             @OA\Property(property="message", type="string", example="billHistory Success"),
     *             @OA\Property(property="content", type="array", @OA\Items(ref="#/components/schemas/BillResource")),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     * )
     */
    public function billHistory($tenantId)
    {
        //authorize
//        $userId = User::where('tenant_id', $tenantId)->pluck('id')->first();
//        if (auth('sanctum')->user()->id != $userId) {
//            return $this->errorResponse('Unathorized', 401);
//        }

        $year = date('Y');

        $billHistory = Bill::where('tenant_id', $tenantId)->whereYear('created_at', $year)->get();

        if ($billHistory->isEmpty()) {
            return $this->successResponse("Bill history is empty",BillResource::collection($billHistory), 200);
        }

        return $this->successResponse("billHistory Success",BillResource::collection($billHistory), 200);
    }
}
