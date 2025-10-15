<?php

namespace App\Http\Controllers\Api\Dashboard;

use Exception;
use App\Models\Occupant;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Enums\RelationshipToTenant;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Dashboard\OccupantResource;

class OccupantController extends Controller
{
    use  ApiResponse ;

    public function index()
    {
        $occupants = Occupant::get();

        return $this->successResponse('Occupants retrieved successfully',OccupantResource::collection($occupants));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'nrc'  => 'nullable',
            'relationshipToTenant' => ['required', new Enum(RelationshipToTenant::class)],
            'tenantId' => 'required|exists:tenants,id'
        ]);

        if($validator->fails())
        {
            return $this->errorResponse($validator->errors(),422);
        }

        $validatedData = $validator->validated();

        try
        {
            $data  = Occupant::create(
                [
                'name'  =>  $validatedData['name'],
                'nrc'   =>  $validatedData['nrc'],
                'relationship_to_tenant' => $validatedData['relationshipToTenant'],
                'tenant_id'      => $validatedData['tenantId'],
                ]
            );

            return $this->successResponse('Occupant created successfully!',new OccupantResource($data),201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),500);
        }
    }

    public function update(Request $request,$id)
    {
        try
        {
            $occupant = Occupant::findOrFail($id);

            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'nrc'  => 'nullable',
                'relationshipToTenant' => ['required', new Enum(RelationshipToTenant::class)]
            ]);

            if($validator->fails())
            {
                return $this->errorResponse($validator->errors(),422);
            }

            $validatedData = $validator->validated();

            $validated = [
                'name' => $validatedData['name'],
                'nrc'  => $validatedData['nrc'],
                'relationship_to_tenant' => $validatedData['relationshipToTenant'],
            ];

            $occupant->update($validated);

            return $this->successResponse('Occupant information updated successfully!',new OccupantResource($occupant));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),500);
        }
    }

    public function show($id)
    {
        $occupant = Occupant::find($id);

        if(!$occupant)
        {
            return $this->errorResponse('Occupant not found!',404);
        }

        return $this->successResponse('Occupant information found successfully!',new OccupantResource($occupant));
    }
}
