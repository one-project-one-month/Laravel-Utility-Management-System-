<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Helpers\PostgresHelper;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Dashboard\TenantResource;



/**
 * @OA\Tag(
 * name="Tenants",
 * description="API Endpoints for managing tenants"
 * )
 */
class TenantController extends Controller
{
    use ApiResponse, PostgresHelper;


    /**
     * @OA\Get(
     * path="/api/v1/tenants",
     * summary="Get a list of tenants",
     * description="Returns a paginated list of all tenants.",
     * tags={"Tenants"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Tenants retrieved successfully"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/TenantResource")),
     * @OA\Property(property="pagination", type="object",
     * @OA\Property(property="total", type="integer", example=50),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=4)
     * )
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Tenants not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index()
    {
        $tenants = Tenant::with('occupants')
            ->paginate(config('pagination.perPage'));

        if ($tenants->isEmpty()) {
            return $this->errorResponse('Tenants not found', 404);
        }

        return $this->successResponse(
            'Tenants retrieved successfully',
            $this->buildPaginatedResourceResponse(TenantResource::class, $tenants),
        );
    }


     /**
     * @OA\Post(
     * path="/api/v1/tenants",
     * summary="Create a new tenant",
     * description="Creates a new tenant record.",
     * tags={"Tenants"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"roomId", "name", "nrc", "email", "phNumber", "emergencyNo"},
     * @OA\Property(property="roomId", type="string", format="uuid", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
     * @OA\Property(property="name", type="array", @OA\Items(type="string"), example={"John Doe", "Jane Doe"}),
     * @OA\Property(property="nrc", type="array", @OA\Items(type="string"), example={"12/ABC(N)123456"}),
     * @OA\Property(property="email", type="array", @OA\Items(type="string", format="email"), example={"john.doe@example.com"}),
     * @OA\Property(property="phNumber", type="array", @OA\Items(type="string"), example={"09123456789"}),
     * @OA\Property(property="emergencyNo", type="array", @OA\Items(type="string"), example={"09987654321"})
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Tenant created successfully",
     * @OA\JsonContent(ref="#/components/schemas/TenantResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=500, description="Tenant creation failed"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'roomId' => ['required', 'uuid', 'exists:rooms,id'],
            'name' => ['required'],
            'nrc' => ['required'],
            'email' => ['required'],
            'phNumber' => ['required'],
            'emergencyNo' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $validatedData = $validator->validated();
        $tenantData = [
            'room_id'       => $validatedData['roomId'],
            'name'          => $validatedData['name'],
            'nrc'           => $validatedData['nrc'],
            'email'         => $validatedData['email'],
            'phone_no'      => $validatedData['phNumber'],
            'emergency_no'  => $validatedData['emergencyNo'],
        ];

        try {
            $tenant = Tenant::create($tenantData);

            return $this->successResponse(
                'Tenant created successfully',
                new TenantResource($tenant),
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Tenant creation failed',
                500
            );
        }
    }



    /**
     * @OA\Get(
     * path="/api/v1/tenants/{id}",
     * summary="Get a single tenant",
     * description="Returns the details of a specific tenant by their ID.",
     * tags={"Tenants"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the tenant",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Tenant find successful",
     * @OA\JsonContent(ref="#/components/schemas/TenantResource")
     * ),
     * @OA\Response(response=404, description="Tenant did not find"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */

    public function show(string $id)
    {
        $tenant =Tenant::find($id);

        if(!$tenant){
            return $this->errorResponse(
                'Tenant did not find', 404
            );
        }

        return $this->successResponse(
            'Tenent find successful',
            new TenantResource($tenant),200
        );
    }


     /**
     * @OA\Put(
     * path="/api/v1/tenants/{id}",
     * summary="Update an existing tenant",
     * description="Updates the details of an existing tenant.",
     * tags={"Tenants"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the tenant to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"roomId", "name", "email", "phNumber", "emergencyNo"},
     * @OA\Property(property="roomId", type="string", format="uuid", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
     * @OA\Property(property="name", type="array", @OA\Items(type="string"), example={"Johnathan Doe"}),
     * @OA\Property(property="nrc", type="array", @OA\Items(type="string"), example={"12/DEF(N)654321"}),
     * @OA\Property(property="email", type="array", @OA\Items(type="string", format="email"), example={"johnathan.doe@example.com"}),
     * @OA\Property(property="phNumber", type="array", @OA\Items(type="string"), example={"09112233445"}),
     * @OA\Property(property="emergencyNo", type="array", @OA\Items(type="string"), example={"09556677889"})
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Tenant updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/TenantResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=404, description="Tenant not find"),
     * @OA\Response(response=500, description="Tenant update failed"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'roomId' =>['required'],
            'name' => ['required'],
            'nrc'  =>  ['nullable'],
            'email' => ['required'],
            'phNumber' => ['required'],
            'emergencyNo' =>['required'],
        ]);

        if($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        try{
            $tenant = Tenant::find($id);

            if(!$tenant) {
                return $this->errorResponse('Tenant not find', 404);
            }

            $validatedData = $validator->validated();

            $tenantData = [
                'room_id'       => $validatedData['roomId'],
                'name'         => $validatedData['name'],
                'nrc'          => $validatedData['nrc'],
                'email'        => $validatedData['email'],
                'phone_no'     => $validatedData['phNumber'],
                'emergency_no' => $validatedData['emergencyNo'],
            ];

            $tenant->update($tenantData);

            return $this->successResponse(
                'Tenant updated successfully',
                new TenantResource($tenant), 200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Tenant update failed: '. $e->getMessage(),
            500); // changed (,) to (.) for proper string joining
        }
    }
}
