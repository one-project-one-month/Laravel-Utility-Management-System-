<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Dashboard\ContractResource;
use App\Models\Room;

use function Pest\Laravel\json;

class ContractController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $contracts = Contract::all();
        return $this->successResponse('Contracts retrieved successfully', ContractResource::collection($contracts), 200);
    }

    public function show($id)
    {
        $contract = Contract::find($id);

        if (!$contract) {
            return $this->errorResponse('Contract not found', 404);
        }

        return $this->successResponse(
            'Contract retrieved successfully',
            new ContractResource($contract),
            200
        );
    }

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
        if ($room->status !== 'Avaliable') {
            return $this->errorResponse('Room is not Avaliable', 404);
        };

        $data = [
            'contract_type_id' => $validatedData['contractId'],
            'tenant_id'        => $validatedData['tenantId'],
            'room_id'          => $validatedData['roomNo'],
            'expiry_date'      => $validatedData['expiryDate']
        ];

        $contract = Contract::create($data);
        $room->status = 'Rented';
        $room->save();

        return $this->successResponse('Contract created successfully', new ContractResource($contract), 201);
    }

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

        if ($room->status === 'Available' || $contract->room_id === $validatedData['roomNo']) {

            $data = [
                'contract_type_id' => $validatedData['contractId'],
                'tenant_id'        => $validatedData['tenantId'],
                'room_id'          => $validatedData['roomNo'],
                'expiry_date'      => $validatedData['expiryDate'],
            ];

            $contract->update($data);
            $contract->refresh();

            return $this->successResponse('Contract updated successfully', new ContractResource($contract), 200);
        }

        return $this->errorResponse('Room is not Available', 409);
    }
}
