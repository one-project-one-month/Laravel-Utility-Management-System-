<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerService; 

class CustomerServiceController extends Controller
{
    /**
     * Create Customer Service Request (POST)
     */













    /**
     * Get Customer Service History (GET)
     */
    public function history($id, $status = null)
    {
        $query = CustomerService::where('room_id', $id);

        if ($status) {
            $status = trim($status);
            $query->where('status', 'ILIKE', $status);
        }


        $services = $query->orderBy('issued_date', 'desc')->get();

        return response()->json([
            'message' => 'Customer Service History',
            'data' => $services
        ], 200);
    }

}
