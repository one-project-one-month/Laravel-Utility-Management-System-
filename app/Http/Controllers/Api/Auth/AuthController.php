<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Auth\AuthResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * @OA\Info(
 *     title="Utility Management System API",
 *     version="1.0.0",
 *     description="API documentation for the Laravel Utility Management System",
 * )
 *
 */
class AuthController extends Controller
{
    use ApiResponse, HasApiTokens, HasFactory, Notifiable;

    /**
 * @OA\Post(
 *     path="/v1/auth/login",
 *     summary="Login user and return token",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="Password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Login success"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="user", type="object", example={"id": 1, "name": "John Doe", "email": "user@example.com"}),
 *                 @OA\Property(property="token", type="string", example="1|abcxyz123")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - invalid password",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Your credential is wrong!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Your credentials have not served!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation errors",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 example={"email": {"The email field is required."}, "password": {"The password must be at least 6 characters."}}
 *             )
 *         )
 *     )
 * )
 */

    public function login (Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' =>  'required|email',
            'password' => 'required|min:6|regex:/[0-9]/|regex:/[a-zA-Z]/'
        ]);

        if($validator->fails()) {
            return $this->errorResponse($validator->errors(),422);
        }

        $validatedData = $validator->validated();

        $user = User::where('email',$request->email)->first();

        if(!$user) {
            return $this->errorResponse('Your credentials have not served!',404);
        }

        if(!Hash::check( $validatedData['password'], $user->password )){
            return $this->errorResponse('Your credential is wrong!',401);
        }

        $accessToken = $user->createToken('access-token', ['*'], now()->addHour())->plainTextToken;

        $refreshToken = Str::random(64);

        $user->update([
            'refresh_token' => hash('sha256', $refreshToken),
            'refresh_token_expires_at' => Carbon::now()->addDays(15),
        ]);

        $content = [
            'user'=> $user,
            'accessToken' => $accessToken,
            'refreshToken' =>  $refreshToken
        ];

        return $this->successResponse('Login success',new AuthResource($content),200);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refreshToken');
        $hashed = hash('sha256', $refreshToken);

        $user = User::where('refresh_token', $hashed)
            ->where('refresh_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return $this->errorResponse('Invalid refresh token',401);
        }

        $accessToken = $user->createToken('access-token', ['*'], now()->addHour())->plainTextToken;

        $content = [
            'user'=> $user,
            'accessToken' => $accessToken,
        ];

        return $this->successResponse('successful',new AuthResource($content),200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->update([
            'refresh_token' => null,
            'refresh_token_expires_at' => null,
        ]);

        return response()->json(['message' => 'Logged out']);
    }
}
