<?php

namespace App\Http\Controllers\Api\Client;

//use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Models\CustomerService;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Dashboard\CustomerServiceResource;
use Illuminate\Support\Facades\Validator;

class CustomerServiceController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Post(
     *     path="/api/v1/tenants/{id}/customer-services/create",
     *     summary="Create a customer service request",
     *     description="Allows a tenant to create a new customer service request (e.g., complain, maintenance).",
     *     tags={"Client"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the tenant making the request",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"roomId", "category", "description", "status", "priorityLevel", "issuedDate"},
     *             @OA\Property(property="roomId", type="string", format="uuid", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
     *             @OA\Property(property="category", type="string", enum={"Complain", "Maintenance", "Other"}, example="Maintenance"),
     *             @OA\Property(property="description", type="string", example="The kitchen sink is leaking."),
     *             @OA\Property(property="status", type="string", enum={"Pending", "Ongoing", "Resolved"}, example="Pending"),
     *             @OA\Property(property="priorityLevel", type="string", enum={"Low", "Medium", "High"}, example="Medium"),
     *             @OA\Property(property="issuedDate", type="string", format="date", example="2025-10-05")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer service request created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example="true"),
     *             @OA\Property(property="message", type="string", example="Customer Service created successfully"),
     *             @OA\Property(property="content", ref="#/components/schemas/CustomerServiceResource"),
     *             @OA\Property(property="status", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example="false"),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\Property(property="roomId", type="array", example={"The room id field is required."}, @OA\Items(type="string")),
     *                 @OA\Property(property="category", type="array", example={"The category field is required."}, @OA\Items(type="string")),
     *                 @OA\Property(property="description", type="array", example={"The description field is required."}, @OA\Items(type="string")),
     *                 @OA\Property(property="status", type="array", example={"The status field is required."}, @OA\Items(type="string")),
     *                 @OA\Property(property="priorityLevel", type="array", example={"The priority level field is required."}, @OA\Items(type="string")),
     *                 @OA\Property(property="issueDate", type="array", example={"The issue date field is required."}, @OA\Items(type="string")),
     *             ),
     *             @OA\Property(property="status", type="integer", example=422)
     *         )

     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     * )
     */
    public function create(Request $request, $tenantId)
    {
        //authorize
        //        $userId = User::where('tenant_id' , $tenantId)->value('id');
        //         if (auth('sanctum')->user()->id != $userId) {
        //            return $this->errorResponse('Unathorized', 401);
        //        }

        $validator = Validator::make($request->post(), [
            'roomId'        => 'required|uuid|exists:rooms,id',
            'category'      => 'required|in:Complain,Maintenance,Other',
            'description'   => 'required|string',
            'status'        => 'required|in:Pending,Ongoing,Resolved',
            'priorityLevel' => 'required|in:Low,Medium,High',
            'issuedDate'    => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $validated = $validator->validated();

        $data = [
            'room_id'        => $validated['roomId'],
            'category'       => $validated['category'],
            'description'    => $validated['description'],
            'status'         => $validated['status'],
            'priority_level' => $validated['priorityLevel'],
            'issued_date'    => $validated['issuedDate'],
        ];

        $customerService = CustomerService::create($data);

        return $this->successResponse('Customer Service created successfully', new CustomerServiceResource($customerService), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tenants/{id}/customer-services/history/{status}",
     *     summary="Get customer service history for a tenant",
     *     description="Retrieves a list of customer service requests for a specific tenant, optionally filtered by status.",
     *     tags={"Client"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the tenant to retrieve history for",
     *         @OA\Schema(type="string", format="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="path",
     *         required=false,
     *         description="Filter by status (e.g., Pending, Resolved)",
     *         @OA\Schema(type="string", enum={"Pending", "Ongoing", "Resolved"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example="true"),
     *             @OA\Property(property="message", type="string", example="Customer Service History"),
     *             @OA\Property(property="content", type="array", @OA\Items(ref="#/components/schemas/CustomerServiceResource")),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     * )
     */
    public function history($tenantId, Request $request)
    {
        $tenant = Tenant::find($tenantId);
        if (!$tenant) return $this->errorResponse('Tenant not found', 404);

        $query = CustomerService::query()->where('room_id', $tenant->room_id);
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $services = $query
            ->orderBy('created_at', 'desc')
            ->paginate(config('pagination.perPage'));

        if ($services->isEmpty()) {
            return $this->successResponse('No customer service requests found.', [], 200);
        }
        return $this->successResponse(
            'Customer Service History',
            $this->buildPaginatedResourceResponse(
                CustomerServiceResource::class,
                $services
            )
        );
    }
}
