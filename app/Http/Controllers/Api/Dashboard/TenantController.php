<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Dashboard\TenantResource;


class TenantController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $tenants = Tenant::paginate(10);

        return $this->successResponse(
            'Tenants retrieved successfully',
            TenantResource::collection($tenants),
            200
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'roomId' => ['required'],
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
            'names'         => $this->stringToPgArrayString($validatedData['name']),
            'nrcs'          => $this->stringToPgArrayString($validatedData['nrc']),
            'emails'        => $this->stringToPgArrayString($validatedData['email']),
            'phone_nos'     => $this->stringToPgArrayString($validatedData['phNumber']),
            'emergency_nos' => $this->stringToPgArrayString($validatedData['emergencyNo']),
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
                return $this->errorResponse('Tentant not find', 404);
            }

            $validatedData = $validator->validated();

            $tenantData = [
                'room_id'       => $validatedData['roomId'],
                'names'         => $this->stringToPgArrayString($validatedData['name']),
                'nrcs'          => $this->stringToPgArrayString($validatedData['nrc']),
                'emails'        => $this->stringToPgArrayString($validatedData['email']),
                'phone_nos'     => $this->stringToPgArrayString($validatedData['phNumber']),
                'emergency_nos' => $this->stringToPgArrayString($validatedData['emergencyNo']),
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

    private function stringToPgArrayString($textString){

        $textArray = explode(',', $textString);
        $textArray = collect($textArray)->map(function($value){
            return trim($value);
        })->toArray();

        $pgArrayString = "{".implode(",", $textArray). "}";
        return $pgArrayString;
    }
}
