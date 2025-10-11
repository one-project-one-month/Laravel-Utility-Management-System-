<?php
namespace App\Http\Controllers\Api\Client;

//use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Models\CustomerService;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 * name="Client Customer Service",
 * description="Endpoints for tenants to manage their customer service requests"
 * )
 */
class CustomerServiceController extends Controller
{
    use ApiResponse;
    /**
     * Create Customer Service Request (POST)
     */


    /**
     * @OA\Post(
     * path="/api/v1/tenants/{tenantId}/customer-services/create",
     * summary="Create a customer service request",
     * description="Allows a tenant to create a new customer service request (e.g., complain, maintenance).",
     * tags={"Client Customer Service"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="tenantId",
     * in="path",
     * required=true,
     * description="The ID of the tenant making the request",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"roomId", "category", "description", "status", "priorityLevel", "issuedDate"},
     * @OA\Property(property="roomId", type="string", format="uuid", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
     * @OA\Property(property="category", type="string", enum={"Complain", "Maintenance", "Other"}, example="Maintenance"),
     * @OA\Property(property="description", type="string", example="The kitchen sink is leaking."),
     * @OA\Property(property="status", type="string", enum={"Pending", "Ongoing", "Resolved"}, example="Pending"),
     * @OA\Property(property="priorityLevel", type="string", enum={"Low", "Medium", "High"}, example="Medium"),
     * @OA\Property(property="issuedDate", type="string", format="date", example="2025-10-05")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Customer service request created successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Customer Service created successfully"),
     * @OA\Property(property="data", ref="#/components/schemas/CustomerServiceResource")
     * )
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function create(Request $request, $tenantId)
    {
        //authorize
//        $userId = User::where('tenant_id' , $tenantId)->value('id');
//         if (auth('sanctum')->user()->id != $userId) {
//            return $this->errorResponse('Unathorized', 401);
//        }

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

        return $this->successResponse('Customer Service created successfully', $customerService, 201);
    }

    /**
     * Get Customer Service History (GET)
     */

     /**
     * @OA\Get(
     * path="/api/v1/tenants/{id}/customer-services/history/{status?}",
     * summary="Get customer service history for a tenant",
     * description="Retrieves a list of customer service requests for a specific tenant, optionally filtered by status.",
     * tags={"Client Customer Service"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="The ID of the tenant (or room ID associated with the tenant) to retrieve history for",
     * @OA\Schema(type="string", format="uuid")
     * ),
     * @OA\Parameter(
     * name="status",
     * in="path",
     * required=false,
     * description="Filter by status (e.g., Pending, Resolved)",
     * @OA\Schema(type="string", enum={"Pending", "Ongoing", "Resolved"})
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Customer Service History"),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CustomerServiceResource"))
     * )
     * ),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function history($tenantId, $status = null)
    {

        //authorize
//        $userId = User::where('tenant_id' , $tenantId)->value('id');
//         if (auth('sanctum')->user()->id != $userId) {
//            return $this->errorResponse('Unathorized', 401);
//        }
        $tenant = Tenant::find($tenantId);
        $query = CustomerService::where('room_id', $tenant->room_id);

        if ($status) {
            $status = trim($status);
            $query->where('status', 'ILIKE', $status);
        }

        $services = $query->orderBy('issued_date', 'desc')->get();

        return $this->successResponse('Customer Service History', $services);
    }

}
