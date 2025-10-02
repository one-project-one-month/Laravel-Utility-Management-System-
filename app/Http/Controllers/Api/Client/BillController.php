<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Dashboard\BillResource;
use App\Models\Bill;
use Illuminate\Http\Request;

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
}
