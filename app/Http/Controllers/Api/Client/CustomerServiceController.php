<?php
namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\CustomerService;
use Illuminate\Http\Request;

class CustomerServiceController extends Controller
{
    /**
     * Create Customer Service Request (POST)
     */

    public function create(Request $request, $tenantId)
    {
        $validated = $request->validate([
            'roomId'        => 'required|uuid|exists:rooms,id',
            'category'      => 'required|in:Complain,Maintenance,Other',
            'description'   => 'required|string',
            'status'        => 'required|in:Pending,Ongoing,Resolved',
            'priorityLevel' => 'required|in:Low,Medium,High',
            'issuedDate'    => 'required|date',
        ]);

        $data = [
            'room_id'        => $validated['roomId'],
            'category'       => $validated['category'],
            'description'    => $validated['description'],
            'status'         => $validated['status'],
            'priority_level' => $validated['priorityLevel'],
            'issued_date'    => $validated['issuedDate'],
        ];

        $customerService = CustomerService::create($data);

        return response()->json([
            'message' => 'Customer Service created successfully',
            'data'    => $customerService,
        ], 201);
    }

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
            'data'    => $services,
        ], 200);
    }

}
