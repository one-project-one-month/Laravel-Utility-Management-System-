<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\ContractTypeResource;
use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractTypeController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of contract-types.
     */
    public function index()
    {
        // retrieve a list of contract-types with pagination
        $contractTypes = ContractType::paginate(10);

        // return the list of contract-types
        return $this->successResponse(
            'Contract types retrieved successfully',
            ContractTypeResource::collection($contractTypes),
            200
        );
    }

    /**
     * Display a specific contract-type.
     */
    public function show(String $id)
    {
        // retrieve a single contract-type
        $contractType = ContractType::find($id);

        // return error if the resource is not found
        if (!$contractType) {
            return $this->errorResponse(
                'Contract type not found',
                404
            );
        }

        // return the contract-type
        return $this->successResponse(
            'Contract type retrieved successfully',
            new ContractTypeResource($contractType),
            200
        );
    }

    /**
     * Create a new contract-type.
     */
    public function store(Request $request)
    {
        // validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'duration' => ['required', 'integer'],
            'price' => ['required', 'decimal:0,2'],
            'facilities' => ['required']
        ]);

        // return error if the validation fails 
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        // format facilities field to appropriate format for storing into postgres text array field
        $validated = $validator->validated();
        $validated['facilities'] = $this->stringToPgArrayString($validated['facilities']);

        // create new contract-type
        try {
            // create new contract-type
            $contractType = ContractType::create($validated);
    
            // return the contract-type
            return $this->successResponse(
                'A new Contract Type created successfully',
                new ContractTypeResource($contractType),
                200
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
        // validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'duration' => ['required', 'integer'],
            'price' => ['required', 'decimal:0,2']
        ]);

        // return error if the validation fails
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        // update the contract-type
        try {

            // reterieve the contract-type to update
            $contractType = ContractType::find($id);

            // return error if the contract-type does not exists
            if (!$contractType) {
                return $this->errorResponse([
                    'Contract Type not found',
                    404
                ]);
            }

            // update the contract-type
            $contractType->update([
                'name' => $request->name,
                'duration' => $request->duration,
                'price' => $request->price
            ]);
            
            // return the contract-type
            return $this->successResponse(
                'Contract Type updated successfully',
                new ContractTypeResource($contractType),
                201
            );

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Contract type update fails: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * format the string to postgres array string
     */
    private function stringToPgArrayString($textString)
    {
        $textArray = explode(',', $textString);

        // trim whitespaces
        $textArray = collect($textArray)->map(function ($value) {
            return trim($value);
        })->toArray();

        // change to text array format
        $pgArrayString = "{" . implode(",", $textArray) . "}";

        return $pgArrayString;
    }
}
