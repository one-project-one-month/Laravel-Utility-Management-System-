<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Client\ReceiptResource;
use App\Models\Receipt;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Query\Builder;


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
        $receipt = Receipt::where('invoice_id', function (Builder $query) use ($userId) {
            $query->select('id')->from('invoices')
                ->where('bill_id', function (Builder $query) use ($userId) {
                    $query->select('id')->from('bills')
                        ->where('user_id', $userId)->limit(1);
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
}   public function history( $tenantId ){

    $tenant = Tenant::find($tenantId);

    if(!$tenant){
        return $this->errorResponse('Tenant not found', 404);
    }

    $userId = User::where('tenant_id', $tenantId)->value('id')->toArray();
    if($userId->contains(Auth::user()->id)){
        //
    }




}
