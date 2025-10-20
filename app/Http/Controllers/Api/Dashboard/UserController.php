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


/**
 * @OA\Tag(
 * name="Users",
 * description="API Endpoints for managing users"
 * )
 */
class UserController extends Controller
{
    use ApiResponse, HasApiTokens, Notifiable;


     /**
     * @OA\Post(
     * path="/api/v1/users",
     * summary="Create a new user",
     * description="Creates a new user account.",
     * tags={"Users"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"userName", "email", "password", "role"},
     * @OA\Property(property="userName", type="string", example="John Doe"),
     * @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     * @OA\Property(property="password", type="string", format="password", example="Password123"),
     * @OA\Property(property="role", type="string", enum={"Admin", "Tenant", "Staff"}, example="Tenant"),
     * @OA\Property(property="tenantId", type="integer", description="Required if role is Tenant", example=1)
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="User created successfully",
     * @OA\JsonContent(ref="#/components/schemas/UserResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=500, description="User creation failed"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
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



    /**
     * @OA\Get(
     * path="/api/v1/users",
     * summary="Get a list of users",
     * description="Returns a paginated list of all users.",
     * tags={"Users"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Users retrieved successfully"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/UserResource")),
     * @OA\Property(property="pagination", type="object",
     * @OA\Property(property="total", type="integer", example=50),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=4)
     * )
     * )
     * )
     * ),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    //index users
    public function index(Request $request)
    {
        $role = $request->query('role');

        $query = User::with(['tenant']);

        if($role) {
            $query->where('role', $role);
        }
        $users = $query->paginate(config('pagination.perPage'));

        return $this->successResponse(
            'Users retrieved successfully',
            $this->buildPaginatedResourceResponse(UserResource::class, $users),
        );
    }

     /**
     * @OA\Put(
     * path="/api/v1/users/{id}",
     * summary="Update an existing user",
     * description="Updates the details of an existing user.",
     * tags={"Users"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the user to update",
     * @OA\Schema(type="string")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"userName", "email", "role", "isActive"},
     * @OA\Property(property="userName", type="string", example="Johnathan Doe"),
     * @OA\Property(property="email", type="string", format="email", example="johnathandoe@example.com"),
     * @OA\Property(property="password", type="string", format="password", description="Optional. Min 6 chars, 1 letter, 1 number", example="NewPass123"),
     * @OA\Property(property="role", type="string", enum={"Admin", "Tenant", "Staff"}, example="Staff"),
     * @OA\Property(property="tenantId", type="integer", example=null),
     * @OA\Property(property="isActive", type="boolean", example=true)
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="User Updated Successfully",
     * @OA\JsonContent(ref="#/components/schemas/UserResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=404, description="User not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */

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
            'tenant_id' => $request->tenantId??null,
            'is_active' => $request->isActive
        ];

        if (isset($request->password)) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return $this->successResponse('User Updated Successfully', new UserResource($user));
    }


     /**
     * @OA\Get(
     * path="/api/v1/users/{id}",
     * summary="Get a single user",
     * description="Returns the details of a specific user by their ID.",
     * tags={"Users"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the user",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="User Fetched Successfully",
     * @OA\JsonContent(ref="#/components/schemas/UserResource")
     * ),
     * @OA\Response(response=404, description="User not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
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
