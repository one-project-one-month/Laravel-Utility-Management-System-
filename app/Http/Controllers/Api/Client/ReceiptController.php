<?php

namespace App\Http\Controllers\Api\Client;

//use App\Models\User;
use App\Models\Tenant;
use App\Models\Receipt;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Api\Client\ReceiptResource;

class ReceiptController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/api/v1/tenants/{id}/receipts/latest",
     *     summary="Get the latest receipt for a tenant",
     *     description="Retrieves the most recent receipt for a specific tenant.",
     *     tags={"Client"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the tenant",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Receipt retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example="true"),
     *             @OA\Property(property="message", type="string", example="Receipt retrieved successful"),
     *             @OA\Property(property="content", ref="#/components/schemas/ClientReceiptResource"),
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
     *     @OA\Response(
     *         response=404,
     *         description="Tenant not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example="false"),
     *             @OA\Property(property="message", type="string", example="Tenant not found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function latest($tenantId)
    {
        $tenant = Tenant::find($tenantId);
        if (! $tenant) {
            return $this->errorResponse('Tenant not found', 404);
        }

        // Authorize
//        $userId = User::where('tenant_id', $tenantId)->pluck('id')->first();
//        if (auth('sanctum')->user()->id != $userId) {
//            return $this->errorResponse('Unathorized', 401);
//        }

        // get latest receipt
        $receipt = Receipt::with('invoice.bill')
            ->whereHas('invoice.bill', function (Builder $query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })->latest()->first();

        return $this->successResponse(
            'Receipt retrieved successful',
            new ReceiptResource($receipt),
            200
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tenants/{id}/receipts/history",
     *     summary="Get the receipt history for a tenant",
     *     description="Retrieves the bill history for the current year for a specific tenant.",
     *     tags={"Client"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the tenant",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Receipt retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example="true"),
     *             @OA\Property(property="message", type="string", example="Receipt History retrieved successful"),
     *             @OA\Property(property="content", type="array", @OA\Items(ref="#/components/schemas/ClientReceiptResource")),
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
     *     @OA\Response(
     *         response=404,
     *         description="Tenant not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example="false"),
     *             @OA\Property(property="message", type="string", example="Tenant not found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function history($tenantId){

        // tenant data retrieve
        $tenant = Tenant::find($tenantId);

        // return error response if no tenant data
        if(!$tenant){
            return $this->errorResponse('Tenant not found', 404);
        }

        // create user id array with same tenant id
//        $userId = User::where('tenant_id', $tenantId)->pluck('id');

        // return error response if no auth user id in array
//        if(!$userId->contains(Auth::user()->id)){
//            return $this->errorResponse('Unathorized', 401);
//        }

        try{

            $receipts = Receipt::with('invoice.bill')
            ->whereHas('invoice.bill', function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })
            ->orderBy('receipts.paid_date','desc')
            //->skip(1)
            ->paginate(config('pagination.perPage'));

            if ($receipts->isEmpty()) {
                return $this->successResponse(
                    'No receipt history found for this tenant',
                    [], 200
                );
            }

            return $this->successResponse(
                'Receipt History retrieved successful',
                $this->buildPaginatedResourceResponse(ReceiptResource::class, $receipts),
                200
            );

        } catch (\Exception $e) {
            return $this->errorResponse(
               'Failed to fetch receipt history: ' . $e->getMessage(),
               500
           );
       }
    }
}
