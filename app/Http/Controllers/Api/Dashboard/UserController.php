<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Api\Dashboard\UserResource;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponse, HasApiTokens, Notifiable;

    //user create
    public function store(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'userName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6|regex:/[0-9]/|regex:/[a-zA-Z]/',
            'role' => 'required|in:Admin,Tenant,Staff',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        //create user
        try {
            $user = User::create([
                'user_name' => $request->userName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'tenant_id' => $request->tenantId,
            ]);

            return $this->successResponse('User created successfully', new UserResource($user), 201);

        } catch (\Exception $e) {
            return $this->errorResponse('User creation failed: ' . $e->getMessage(), 500);
        }
    }

    //index users
    public function index()
    {
        $users = User::with(['tenant'])
            ->paginate(config('pagination.perPage'));

        return $this->successResponse(
            'Users retrieved successfully',
            $this->buildPaginatedResourceResponse(UserResource::class, $users),
        );
    }

    //Users Update Method
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->errorResponse('User not found.', 404);
        }

        //validation
        $validator = Validator::make($request->all(), [
            'userName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|min:6|regex:/[0-9]/|regex:/[a-zA-Z]/',
            'role' => 'required|in:Admin,Tenant,Staff',
            'tenantId' => 'nullable',
            'isActive' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        };

        //update user
        $userData = [
            'user_name' => $request->userName,
            'email' => $request->email,
            'role' => $request->role,
            'tenant_id' => $request->tenantId,
            'is_active' => $request->isActive
        ];

        if (isset($request->password)) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return $this->successResponse('User Updated Successfully', new UserResource($user));
    }

    //User Show
    public function show($id)
    {
        $user = User::find($id);
        $user->load('tenant');

        if (!$user) {
            return $this->errorResponse('User not found.', 404);
        }

        return $this->successResponse('User Fetched Successfully', new UserResource($user));
    }
}
