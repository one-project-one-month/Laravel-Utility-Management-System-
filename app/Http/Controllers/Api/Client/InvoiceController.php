<?php

namespace App\Http\Controllers\Api\Client;

use Carbon\Carbon;
//use App\Models\User;
use App\Models\Tenant;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\InvoiceResource;

class InvoiceController extends Controller
{
    use ApiResponse;


     /**
     * @OA\Get(
     *     path="/api/v1/tenants/{id}/invoices/latest",
     *     summary="Get the latest invoice for a tenant",
     *     description="Retrieves the most recent invoice for a specific tenant.",
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
     *         description="Latest invoice retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example="true"),
     *             @OA\Property(property="message", type="string", example="Latest invoice retrieved successfully"),
     *             @OA\Property(property="content", ref="#/components/schemas/ClientInvoiceResource"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found for this tenant",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="false"),
     *              @OA\Property(property="message", type="string", example="Invoice not found for this tenant"),
     *              @OA\Property(property="status", type="integer", example=404)
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     * )
     */
        public function latest($id)
    {
        //authorize
//        $userId = User::where('tenant_id', $id)->value('id');
//        if (auth('sanctum')->user()->id != $userId) {
//            return $this->errorResponse('Unathorized', 401);
//        }

        $tenant = Tenant::find($id);
        if (!$tenant) {
            return $this->errorResponse(
                'Tenant not found', 404
            );
        }

        $invoice = Invoice::with('bill.totalUnit')
            ->whereHas('bill', function ($query) use ($tenant) {
                $query->where('room_id', $tenant->room_id);
            })->latest()->first();

        if (! $invoice) {
            return $this->successResponse(
                'Invoice not found for this tenant',
                [], 404
            );
        }

        return $this->successResponse(
            'Latest invoice retrieved successfully',
            new InvoiceResource($invoice), 200
        );

    }

       /**
     * @OA\Get(
     *     path="/api/v1/tenants/{id}/invoices/history",
     *     summary="Get invoice history for a tenant",
     *     description="Retrieves the invoice history (excluding the current month) for a specific tenant.",
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
     *         description="Invoice history retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example="true"),
     *             @OA\Property(property="message", type="string", example="Invoice history retrieved successfully"),
     *             @OA\Property(property="content", type="array", @OA\Items(ref="#/components/schemas/ClientInvoiceResource")),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tenant not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="false"),
     *              @OA\Property(property="message", type="string", example="Tenant not found"),
     *              @OA\Property(property="status", type="integer", example=404)
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     ),
     * )
     */
    public function history(Request $request, $tenant_id)
    {
        //authorize
//        $userId = User::where('tenant_id', $tenant_id)->value('id');
//        if (auth('sanctum')->user()->id != $userId) {
//            return $this->errorResponse('Unathorized', 401);
//        }

        $tenant =Tenant::find($tenant_id);

        if(!$tenant){
            return $this->errorResponse(
                'Tenant did not find', 404
            );
        }

        try {
            $startOfMonth = Carbon::now()->startOfMonth();

            $invoices = Invoice::with('bill.totalUnit')
                ->whereHas('bill', function ($query) use ($tenant, $startOfMonth) {
                    $query->where('room_id', $tenant->room_id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(config('pagination.perPage'));

            if ($invoices->isEmpty()) {
                return $this->successResponse(
                    'No invoice history found for this tenant',
                    [], 200
                );
            }

            return $this->successResponse(
                'Invoice history retrieved successfully',
                $this->buildPaginatedResourceResponse(InvoiceResource::class, $invoices)
                , 200
            );
        } catch (\Exception $e) {
             return $this->errorResponse(
                'Failed to fetch invoice history: ' . $e->getMessage(),
                500
            );
        }
    }
}
