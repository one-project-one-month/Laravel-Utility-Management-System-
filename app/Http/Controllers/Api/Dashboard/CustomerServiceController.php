<?php

namespace App\Http\Controllers\Api\Dashboard;

use Illuminate\Http\Request;
use App\Models\CustomerService;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Dashboard\CustomerServiceResource;

class CustomerServiceController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $services = CustomerService::orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(config('pagination.perPage'));

        if ($services->isEmpty()) {
            return $this->errorResponse('Customer Services not found', 404);
        }

        return $this->successResponse(
            'CustomerServices retrieved successfully.',
            $this->buildPaginatedResourceResponse(CustomerServiceResource::class, $services)
        );
    }

    // Show
    public function show($id)
    {
        $service = CustomerService::find($id);

        if (!$service) {
            return $this->errorResponse(
                message: 'The customer service you are looking for does not exist!',
                status: 404
            );
        }

        return $this->successResponse(
            message: "CustomerService found successful!",
            content: new CustomerServiceResource($service)
        );
    }

    // Update
    public function update(Request $request, $id)
    {
        try {
            $service = CustomerService::find($id);

            if (!$service) {
                return $this->errorResponse(
                    message: 'The customer service you are trying to update does not exist!',
                    status: 404
                );
            }

            $validator = Validator::make($request->all(), [
                'roomId'        => 'sometimes|exists:rooms,id',
                'category'      => 'sometimes|in:Complain,Maintenance,Other',
                'description'   => 'sometimes|string',
                'status'        => 'sometimes|in:Pending,Ongoing,Resolved',
                'priorityLevel' => 'sometimes|in:Low,Medium,High',
                'issuedDate'    => 'sometimes|date',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            $validated = $validator->validated();

            $service->update([
                'room_id'        => $validated['roomId'] ?? $service->room_id,
                'category'       => $validated['category'] ?? $service->category,
                'description'    => $validated['description'] ?? $service->description,
                'status'         => $validated['status'] ?? $service->status,
                'priority_level' => $validated['priorityLevel'] ?? $service->priority_level,
                'issued_date'    => $validated['issuedDate'] ?? $service->issued_date,
            ]);

            return $this->successResponse(
                message: 'Customer Service updated successfully!',
                content: new CustomerServiceResource($service)
            );

        } catch (\Exception $e) {
              return $this->errorResponse(
                $e->getMessage(),
                500
            );
        }

    }
}
