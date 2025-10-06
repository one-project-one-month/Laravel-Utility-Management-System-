<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Dashboard\BillResource;
use App\Models\Bill;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * @OA\Tag(
 * name="Client Bills",
 * description="Endpoints for tenants to view their billing information"
 * )
 */
class BillController extends Controller
{
    use ApiResponse;


     /**
     * @OA\Get(
     * path="/api/tenants/{id}/bills/latest",
     * summary="Get the latest bill for a tenant",
     * description="Retrieves the most recent bill for a specific tenant based on the user ID.",
     * tags={"Client Bills"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="The User UUID of the tenant",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="latestBill Success"),
     * @OA\Property(property="data", ref="#/components/schemas/BillResource")
     * )
     * ),
     * @OA\Response(response=404, description="Bill not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function latestBill($userId)
    {
        $latestBill = Bill::where('user_id', $userId)
                        ->orderBy('created_at', 'desc')
                        ->first();

        return $this->successResponse("latestBill Success",new BillResource($latestBill), 200);
    }



     /**
     * @OA\Get(
     * path="/api/tenants/{id}/bills/history",
     * summary="Get the bill history for a tenant",
     * description="Retrieves the bill history for the current year for a specific tenant.",
     * tags={"Client Bills"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="The User UUID of the tenant",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="billHistory Success"),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BillResource"))
     * )
     * ),
     * @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function billHistory($userId)
    {
        if(Auth::user()->id != $userId) {
            return $this->errorResponse('Unathorized', 401);
        }

        $year = date('Y');

        $billHistory = Bill::where('user_id', $userId)->whereYear('created_at', $year)->get();

        if ($billHistory->isEmpty()) {
            return $this->successResponse("Bill history is empty",BillResource::collection($billHistory), 200);
        }

        return $this->successResponse("billHistory Success",BillResource::collection($billHistory), 200);
    }
}
