<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Api\UserResource;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class UserController extends Controller
{
    use ApiResponse, HasApiTokens, HasFactory, Notifiable;

    //user create
    public function create(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(),[
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
                'tenant_id' => $request->tenant_id,
            ]);

            return $this->successResponse('User created successfully', new UserResource($user), 201);

        } catch (\Exception $e) {
            return $this->errorResponse('User creation failed: ' . $e->getMessage(), 500);
        }
    }

    //index users
    public function index(){
        $users = User::paginate(10);

        return $this->successResponse('Users retrieved successfully', UserResource::collection($users), 200);
    }

    //Users Update Method
    public function update(Request $request, $id) {
        $user = User::find($id);
        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        
        //validation
        $validator = $request->validate([
            'userName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users'.$id,
            'password' => 'required|min:6|regex:/[0-9]/|regex:/[a-zA-Z]/',
            'role' => 'required|in:Admin,Tenant,Staff',
        ]);

        //update user
        if(isset($validated['name'])) $user->name = $validated['name'];
        if(isset($validated['email'])) $user->name = $validated['email'];
        if(isset($validated['password'])) $user->name = $validated['password'];
        if(isset($validated['role'])) $user->name = $validated['role'];

        $user->save();

        return response()->json([
            'message' => 'User Updated Successfully',
            'user' => $user
        ], 200);
    }

    //User Show
    public function show($id)
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'message' => 'User Fetched Successfully',
            'user' => $user
        ], 200);
    }
}
