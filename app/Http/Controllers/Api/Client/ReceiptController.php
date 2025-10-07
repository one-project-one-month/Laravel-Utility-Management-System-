<?php

namespace App\Http\Controllers\Api\Client;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Receipt;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
use App\Http\Resources\Api\Client\ReceiptResource;


/**
 * @OA\Tag(
 * name="Client Receipts",
 * description="Endpoints for tenants to view their receipt information"
 * )
 */
class ReceiptController extends Controller
{
    use ApiResponse;


    /**
     * @OA\Get(
     * path="/api/tenants/{id}/receipts/latest",
     * summary="Get the latest receipt for a tenant",
     * description="Retrieves the most recent receipt for a specific tenant.",
     * tags={"Client Receipts"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="The ID of the tenant",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Receipt retrieved successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Receipt retrieved successful"),
     * @OA\Property(property="data", ref="#/components/schemas/ClientReceiptResource")
     * )
     * ),
     * @OA\Response(response=404, description="Tenant or Receipt not found"),
     * @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function latest($tenantId)
    {
        $tenant = Tenant::find($tenantId);
        if (! $tenant) {
            return $this->errorResponse('Tenant not found', 404);
        }

        // Authorize
        $userId = User::where('tenant_id', $tenantId)->pluck('id')->first();
        if (auth('sanctum')->user()->id != $userId) {
            return $this->errorResponse('Unathorized', 401);
        }

        // get latest receipt
        $receipt = Receipt::where('invoice_id', function (Builder $query) use ($tenantId) {
            $query->select('id')->from('invoices')
                ->where('bill_id', function (Builder $query) use ($tenantId) {
                    $query->select('id')->from('bills')
                        ->where('tenant_id', $tenantId)->limit(1);
                });
        })->latest()->first();

        return $this->successResponse(
            'Receipt retrieved successful',
            new ReceiptResource($receipt),
            200
        );
    }

    /**
     * receipt history
     */
    public function history($tenantId){

        // tenant data retrieve
        $tenant = Tenant::find($tenantId);

        // return error response if no tenant data
        if(!$tenant){
            return $this->errorResponse('Tenant not found', 404);
        }

        // create user id array with same tenant id
        $userId = User::where('tenant_id', $tenantId)->pluck('id');

        // return error response if no auth user id in array
        if(!$userId->contains(Auth::user()->id)){
            return $this->errorResponse('Unathorized', 401);
        }

        try{

            $receipts = Receipt::with('invoice.bill')
            ->whereHas('invoice.bill', function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })
            ->orderBy('receipts.paid_date','desc')
            ->skip(1)
            ->get();

            if ($receipts->isEmpty()) {
                return $this->successResponse(
                    'No receipt history found for this tenant',
                    [], 200
                );
            }

            return $this->successResponse(
                'Receipt retrieved successful',
                ReceiptResource::collection($receipts),
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