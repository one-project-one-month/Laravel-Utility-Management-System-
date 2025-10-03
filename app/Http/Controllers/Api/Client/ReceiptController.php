<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Client\ReceiptResource;
use App\Models\Receipt;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    use ApiResponse;
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
}
