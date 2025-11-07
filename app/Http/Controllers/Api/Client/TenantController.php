<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Client\TenantResource;


class TenantController extends Controller
{
    use ApiResponse;

    public function update(Request $request,$id) {
        $validator = Validator::make($request->all(),[
            'roomId' =>['required'],
            'name' => ['required'],
            'nrc'  =>  ['nullable'],
            'email' => ['required'],
            'phNumber' => ['required'],
            'emergencyNo' =>['required'],
        ]);

        if($validator->fails()) {
            return $this->errorResponse($validator->errors(),422);
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

            $user = User::where('tenant_id',$id)->first();

            $userData = [
                'name' => $validatedData['name'],
                'email'  => $validatedData['email'],
            ];

            $tenant->update($tenantData);
            $user->update($userData);

            return $this->successResponse(
                'Tenant updated successfully',
                new TenantResource($tenant), 200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Tenant update failed: '. $e->getMessage(),
            500);
        }
    }
}
