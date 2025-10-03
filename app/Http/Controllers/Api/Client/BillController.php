<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Dashboard\BillResource;
use App\Models\Bill;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    use ApiResponse;
    //bill_latest
    public function latestBill($userId)
    {
        $latestBill = Bill::where('user_id', $userId)
                        ->orderBy('created_at', 'desc')
                        ->first();

        return $this->successResponse("latestBill Success",new BillResource($latestBill), 200);
    }


    //bill_history

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
