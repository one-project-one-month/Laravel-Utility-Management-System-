<?php

namespace App\Http\Controllers\Api\Dashboard;

use Illuminate\Http\Request;
use App\Models\CustomerService;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Dashboard\CustomerServiceResource;



/**
 * @OA\Tag(
 * name="Customer Services",
 * description="API Endpoints for managing customer service requests"
 * )
 */
class CustomerServiceController extends Controller
{
    use ApiResponse;


    /**
     * @OA\Get(
     * path="/api/v1/customer-services",
     * summary="Get a list of customer service requests",
     * description="Returns a paginated list of all customer service requests.",
     * tags={"Customer Services"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="CustomerServices retrieved successfully."),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/CustomerServiceResource")),
     * @OA\Property(property="pagination", type="object",
     * @OA\Property(property="total", type="integer", example=50),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=4)
     * )
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Customer Services not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
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


     /**
     * @OA\Get(
     * path="/api/v1/customer-services/{id}",
     * summary="Get a single customer service request",
     * description="Returns the details of a specific customer service request by its ID.",
     * tags={"Customer Services"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the customer service request",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="CustomerService found successful!",
     * @OA\JsonContent(ref="#/components/schemas/CustomerServiceResource")
     * ),
     * @OA\Response(response=404, description="Customer service request not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
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



     /**
     * @OA\Put(
     * path="/api/v1/customer-services/{id}",
     * summary="Update an existing customer service request",
     * description="Updates the details of an existing customer service request.",
     * tags={"Customer Services"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the customer service request to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="roomId", type="string", format="uuid", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
     * @OA\Property(property="category", type="string", enum={"Complain", "Maintenance", "Other"}, example="Maintenance"),
     * @OA\Property(property="description", type="string", example="Leaky faucet in the kitchen."),
     * @OA\Property(property="status", type="string", enum={"Pending", "Ongoing", "Resolved"}, example="Ongoing"),
     * @OA\Property(property="priorityLevel", type="string", enum={"Low", "Medium", "High"}, example="Medium"),
     * @OA\Property(property="issuedDate", type="string", format="date", example="2025-10-05")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Customer Service updated successfully!",
     * @OA\JsonContent(ref="#/components/schemas/CustomerServiceResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=404, description="Customer service request not found"),
     * @OA\Response(response=500, description="Internal Server Error"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
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
