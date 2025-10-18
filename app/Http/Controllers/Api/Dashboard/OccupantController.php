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


      /**
     * @OA\Get(
     * path="/api/v1/occupants",
     * summary="Get a list of occupants",
     * description="Returns a paginated list of all Occupants.",
     * tags={"Occupants"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Occupants retrieved successfully"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/OccupantResource")),
     * @OA\Property(property="pagination", type="object",
     * @OA\Property(property="total", type="integer", example=50),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=4)
     * )
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Occupants not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
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
