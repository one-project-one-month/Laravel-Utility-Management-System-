<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Dashboard\BillResource;
use App\Models\Bill;

class BillController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $bills = Bill::with('totalUnit', 'invoice')->get();

        if ($bills->isEmpty()) {
            return $this->errorResponse('Not found!', 404);
        }

        return $this->successResponse('Bills retrieved successfully', BillResource::collection($bills));
    }

    public function show($id)
    {
        $bill = Bill::whereId($id)->with('totalUnit', 'invoice')->first();

        if (! $bill) {
            return $this->errorResponse('Not found!', 404);
        }

        return $this->successResponse('Bill retrieved successfully', new BillResource($bill),
        );
    }
}
