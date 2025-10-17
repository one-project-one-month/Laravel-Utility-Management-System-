<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\PostgresHelper;
use App\Http\Resources\Api\Dashboard\ContractTypeResource;
use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 * name="Contract Types",
 * description="API Endpoints for managing contract types"
 * )
 */
class ContractTypeController extends Controller
{
    use ApiResponse, PostgresHelper;

    /**
     * Display a listing of contract-types.
     */


     /**
     * @OA\Get(
     * path="/api/v1/contract-types",
     * summary="Get a list of contract types",
     * description="Returns a paginated list of all contract types.",
     * tags={"Contract Types"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Contract types retrieved successfully"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/ContractTypeResource")),
     * @OA\Property(property="pagination", type="object",
     * @OA\Property(property="total", type="integer", example=50),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=4)
     * )
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Contract types not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index()
    {
        $contractTypes = ContractType::orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(config('pagination.perPage'));

        if ($contractTypes->isEmpty()) {
            return $this->errorResponse('Contract types not found', 404);
        }

        return $this->successResponse(
            'Contract types retrieved successfully',
            $this->buildPaginatedResourceResponse(ContractTypeResource::class, $contractTypes)
        );
    }

    /**
     * Display a specific contract-type.
     */


    /**
     * @OA\Get(
     * path="/api/v1/contract-types/{id}",
     * summary="Get a single contract type",
     * description="Returns the details of a specific contract type by its ID.",
     * tags={"Contract Types"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the contract type",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/ContractTypeResource")
     * ),
     * @OA\Response(response=404, description="Contract type not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show(String $id)
    {
        $contractType = ContractType::find($id);

        if (!$contractType) {
            return $this->errorResponse(
                'Contract type not found',
                404
            );
        }

        return $this->successResponse(
            'Contract type retrieved successfully',
            new ContractTypeResource($contractType)
        );
    }

    /**
     * Create a new contract-type.
     */

    /**
     * @OA\Post(
     * path="/api/v1/contract-types",
     * summary="Create a new contract type",
     * description="Creates a new contract type with specified details.",
     * tags={"Contract Types"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "duration", "price", "facilities"},
     * @OA\Property(property="name", type="string", example="Standard 1-Year"),
     * @OA\Property(property="duration", type="integer", description="Duration in months", example=12),
     * @OA\Property(property="price", type="number", format="float", example=550.00),
     * @OA\Property(property="facilities", type="string", description="Comma-separated list of facilities", example="Wi-Fi,Air-Con,Laundry")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Contract Type created successfully",
     * @OA\JsonContent(ref="#/components/schemas/ContractTypeResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=500, description="Creation failed"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function store(Request $request)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'duration' => ['required', 'integer'],
            'price' => ['required', 'decimal:0,2'],
            'facilities' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        // format facilities field to appropriate format for storing into postgres text array field
        $validated = $validator->validated();
        $validated['facilities'] = $this->nativeArrayToPgArrayString($validated['facilities']);

        // create new contract-type
        try {
            $contractType = ContractType::create($validated);

            return $this->successResponse(
                'A new Contract Type created successfully',
                new ContractTypeResource($contractType),
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Contract type creation failed',
                500
            );
        }
    }

    /**
     * Update a specific contract-type.
     */


       /**
     * @OA\Put(
     * path="/api/v1/contract-types/{id}",
     * summary="Update an existing contract type",
     * description="Updates the details of an existing contract type.",
     * tags={"Contract Types"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the contract type to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "duration", "price", "facilities"},
     * @OA\Property(property="name", type="string", example="Premium 2-Year"),
     * @OA\Property(property="duration", type="integer", description="Duration in months", example=24),
     * @OA\Property(property="price", type="number", format="float", example=500.00),
     * @OA\Property(property="facilities", type="string", description="Comma-separated list of facilities", example="Wi-Fi,Air-Con,Laundry,Cleaning")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Contract Type updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/ContractTypeResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=404, description="Contract Type not found"),
     * @OA\Response(response=500, description="Update failed"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function update(Request $request, String $id)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'duration' => ['required', 'integer'],
            'price' => ['required', 'decimal:0,2'],
            'facilities' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        // format facilities field to appropriate format for storing into postgres text array field
        $validated = $validator->validated();
        $validated['facilities'] = $this->nativeArrayToPgArrayString($validated['facilities']);

        // update the contract-type
        try {
            $contractType = ContractType::find($id);

            if (!$contractType) {
                return $this->errorResponse([
                    'Contract Type not found',
                    404
                ]);
            }

            $contractType->update($validated);

            return $this->successResponse(
                'Contract Type updated successfully',
                new ContractTypeResource($contractType),
                200
            );

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Contract type update fails: ' . $e->getMessage(),
                500
            );
        }
    }
}
