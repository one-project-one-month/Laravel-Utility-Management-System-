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

        public function latest($id)
    {
        $tenant = Tenant::find($id);

        if (! $tenant) {
            return $this->errorResponse(
                'Tenant not found', 404
            );
        }

        $invoice = Invoice::with('bill')
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
