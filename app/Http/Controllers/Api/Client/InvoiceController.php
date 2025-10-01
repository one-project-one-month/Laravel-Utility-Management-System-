<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Models\Tenant;
use App\Models\Invoice;
use Carbon\Carbon;
use App\Http\Resources\Api\Client\InvoiceResource;

class InvoiceController extends Controller
{
    use ApiResponse;

    public function history(Request $request, $tenant_id)
    {
        $tenant =Tenant::find($tenant_id);

        if(!$tenant){
            return $this->errorResponse(
                'Tenant did not find', 404
            );
        }

        try {
            $startOfMonth = Carbon::now()->startOfMonth();

            $invoices = Invoice::with('bill')
                ->whereHas('bill', function ($query) use ($tenant, $startOfMonth) {
                    $query->where('room_id', $tenant->room_id);
                })
                ->where('created_at', '<', $startOfMonth)
                ->get();

            if ($invoices->isEmpty()) {
                return $this->successResponse(
                    'No invoice history found for this tenant',
                    [], 200
                );
            }

            return $this->successResponse(
                'Invoice history retrieved successfully',
                InvoiceResource::collection($invoices), 200
            );
        } catch (\Exception $e) {
             return $this->errorResponse(
                'Failed to fetch invoice history: ' . $e->getMessage(),
                500
            );
        }
    }
}
