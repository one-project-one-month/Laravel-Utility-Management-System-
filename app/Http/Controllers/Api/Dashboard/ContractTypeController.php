<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\PostgresHelper;
use App\Http\Resources\Api\Dashboard\ContractTypeResource;
use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractTypeController extends Controller
{
    use ApiResponse, PostgresHelper;

    /**
     * Display a listing of contract-types.
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
        $validated['facilities'] = $this->nativeStringToPgArrayString($validated['facilities']);

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
        $validated['facilities'] = $this->nativeStringToPgArrayString($validated['facilities']);

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
