<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Bill;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\Dashboard\BillResource;

class BillController extends Controller
{
    use ApiResponse;
    //bill_latest
    public function latestBill($tenantId)
    {
         // Authorize
        $userId = User::where('tenant_id', $tenantId)->pluck('id')->first();
        if (auth('sanctum')->user()->id != $userId) {
            return $this->errorResponse('Unathorized', 401);
        }

        $latestBill = Bill::where('tenant_id', $tenantId)
                        ->orderBy('created_at', 'desc')
                        ->first();

        return $this->successResponse("latestBill Success",new BillResource($latestBill), 200);
    }


    //bill_history

    public function billHistory($userId)
    {
        //authorize
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
