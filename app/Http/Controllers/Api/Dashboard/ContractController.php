<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Enums\RoomStatus;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Dashboard\ContractResource;
use App\Models\Room;

/**
 * @OA\Tag(
 * name="Contracts",
 * description="API Endpoints for managing contracts"
 * )
 */
class ContractController extends Controller
{
    use ApiResponse;

     /**
     * @OA\Get(
     * path="/api/v1/contracts",
     * summary="Get a list of contracts",
     * description="Returns a paginated list of all contracts.",
     * tags={"Contracts"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Contracts retrieved successfully"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/ContractResource")),
     * @OA\Property(property="pagination", type="object",
     * @OA\Property(property="total", type="integer", example=50),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=4),
     * )
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Contracts not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index()
    {

        $contracts = Contract::latest()->with(['contractType', 'tenant'])->orderBy('id','desc')
            ->paginate(config('pagination.perPage'));

        if ($contracts->isEmpty()) {
            return $this->errorResponse('Contracts not found', 404);
        }

        return $this->successResponse(
            'Contracts retrieved successfully',
            $this->buildPaginatedResourceResponse(ContractResource::class, $contracts)
        );

    }



   /**
     * @OA\Get(
     * path="/api/v1/contracts/{id}",
     * summary="Get a single contract",
     * description="Returns the details of a specific contract by its ID.",
     * tags={"Contracts"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the contract",
     * @OA\Schema(type="string", format="uuid")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/ContractResource")
     * ),
     * @OA\Response(response=404, description="Contract not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show($id)
    {
        $contract = Contract::find($id);

        if (!$contract) {
            return $this->errorResponse('Contract not found', 404);
        }

        return $this->successResponse(
            'Contract retrieved successfully',
            new ContractResource($contract)
        );
    }


    /**
     * @OA\Post(
     * path="/api/v1/contracts",
     * summary="Create a new contract",
     * description="Creates a new contract and assigns a tenant to a room.",
     * tags={"Contracts"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"roomNo", "contractId", "tenantId", "createdDate", "expiryDate"},
     * @OA\Property(property="roomNo", type="string", format="uuid", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
     * @OA\Property(property="contractId", type="integer", example=1),
     * @OA\Property(property="tenantId", type="integer", example=1),
     * @OA\Property(property="createdDate", type="string", format="date", example="2023-10-27"),
     * @OA\Property(property="expiryDate", type="string", format="date", example="2024-10-26")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Contract created successfully",
     * @OA\JsonContent(ref="#/components/schemas/ContractResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=404, description="Room is not available"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'roomNo'      => 'required|uuid|exists:rooms,id',
            'contractId'  => 'required|exists:contract_types,id',
            'tenantId'    => 'required|exists:tenants,id',
            'createdDate' => 'required|date',
            'expiryDate'  => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $validatedData = $validator->validated();

        $room = Room::find($validatedData['roomNo']);

        $roomStatusAvailable = RoomStatus::Available->value;

        if ($room->status !== $roomStatusAvailable) {
            return $this->errorResponse("Room is not " . $roomStatusAvailable, 404);
        };

        $data = [
            'contract_type_id' => $validatedData['contractId'],
            'tenant_id'        => $validatedData['tenantId'],
            'room_id'          => $validatedData['roomNo'],
            'expiry_date'      => $validatedData['expiryDate']
        ];

        $contract = Contract::create($data);
        $room->status = RoomStatus::Rented->value;
        $room->save();

        return $this->successResponse('Contract created successfully', new ContractResource($contract), 201);
    }


    /**
     * @OA\Put(
     * path="/api/v1/contracts/{id}",
     * summary="Update an existing contract",
     * description="Updates the details of an existing contract.",
     * tags={"Contracts"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the contract to update",
     * @OA\Schema(type="string", format="uuid")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"roomNo", "contractId", "tenantId", "createdDate", "expiryDate"},
     * @OA\Property(property="roomNo", type="string", format="uuid", example="e1e2d736-3813-43be-8f1f-1b734cffb327"),
     * @OA\Property(property="contractId", type="integer", example=1),
     * @OA\Property(property="tenantId", type="integer", example=1),
     * @OA\Property(property="createdDate", type="string", format="date", example="2025-10-5"),
     * @OA\Property(property="expiryDate", type="string", format="date", example="2025-10-6")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Contract updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/ContractResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=404, description="Contract not found"),
     * @OA\Response(response=409, description="Room is not available"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'roomNo'      => 'required|uuid|exists:rooms,id',
            'contractId'  => 'required|exists:contract_types,id',
            'tenantId'    => 'required|exists:tenants,id',
            'createdDate' => 'required|date',
            'expiryDate'  => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $validatedData = $validator->validated();

        $room = Room::find($validatedData['roomNo']);
        $contract = Contract::find($id);
        if (!$contract) {
            return $this->errorResponse('Contract not found', 404);
        }

        if ($room->status === RoomStatus::Available->value || $contract->room_id === $validatedData['roomNo']) {

            $data = [
                'contract_type_id' => $validatedData['contractId'],
                'tenant_id'        => $validatedData['tenantId'],
                'room_id'          => $validatedData['roomNo'],
                'expiry_date'      => $validatedData['expiryDate'],
            ];

            $contract->update($data);
            $contract->refresh();

            return $this->successResponse('Contract updated successfully', new ContractResource($contract));
        }

        return $this->errorResponse('Room is not Available', 409);
    }
}
