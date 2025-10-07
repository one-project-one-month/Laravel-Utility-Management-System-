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
 * title="Utility Management System API",
 * version="1.0.0",
 * description="API documentation for the Laravel Utility Management System",
 * )
 * @OA\Components(
 * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * type="http",
 * scheme="bearer",
 * bearerFormat="JWT",
 * description="Enter token in format (Bearer <token>)",
 * in="header",
 * name="Authorization"
 * )
 * )
 * @OA\Security(
 * security={
 * {"bearerAuth": {}}
 *}
 *)
 */
class AuthController extends Controller
{
    use ApiResponse, HasApiTokens, HasFactory, Notifiable;

   /**
     * @OA\Post(
     * path="/api/v1/auth/login",
     * summary="Login user and return tokens",
     * description="Authenticate a user and receive an access token and a refresh token.",
     * tags={"Authentication"},
     * @OA\RequestBody(
     * required=true,
     * description="User credentials",
     * @OA\JsonContent(
     * required={"email","password"},
     * @OA\Property(property="email", type="string", format="email", example="johndoe@gmail.com"),
     * @OA\Property(property="password", type="string", format="password", example="Ks82787294")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Login successful",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Login success"),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(
     * property="user",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="John Doe"),
     * @OA\Property(property="email", type="string", format="email", example="johndoe@gmail.com")
     * ),
     * @OA\Property(property="accessToken", type="string", example="1|aBcDeFgHiJkLmNoPqRsTuVwXyZ123456"),
     * @OA\Property(
     *property="note",
     *type="string",
     *example="The refresh token is stored in an httpOnly cookie named 'refreshToken'."
     *)
     * )
     * )
     * ),
     * @OA\Response(response=401, description="Unauthorized - invalid password"),
     * @OA\Response(response=404, description="User not found"),
     * @OA\Response(response=422, description="Validation errors")
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
            // 'refreshToken' =>  $refreshToken
        ];

        return $this->successResponse('Login success',new AuthResource($content),200)->withCookie(cookie(
           'refreshToken',                 // cookie name
            $refreshToken,                   // cookie value
            60 * 24 * 15,                    // minutes (15 days)
            '/',                             // path
            null,                            // domain
            app()->isLocal() ? false : true, // secure => local false
            true,                            // httpOnly
            false,                           // raw
            'Strict'                         // SameSite           // SameSite option (Strict / Lax / None)
        ));
    }

 /**
 * @OA\Post(
 *     path="/api/v1/auth/refresh",
 *     summary="Refresh the access token",
 *     description="Uses a refresh token stored in an httpOnly cookie to generate a new access token.",
 *     tags={"Authentication"},
 *     @OA\Parameter(
 *         name="refreshToken",
 *         in="cookie",
 *         required=true,
 *         description="Refresh token cookie",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Token refreshed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="successful"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="email", type="string", format="email", example="johndoe@gmail.com")
 *                 ),
 *                 @OA\Property(property="accessToken", type="string", example="2|zYxWvUtSrQpOnMlKjIhGfEdCbA123456")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized - Invalid or expired refresh token"),
 *     @OA\Response(response=422, description="Validation error - refresh_token is required")
 * )
 */

    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refreshToken');

        if (!$refreshToken) {
            return $this->errorResponse('Refresh token not found', 401);
        }

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


     /**
     * @OA\Post(
     * path="/api/v1/auth/logout",
     * summary="Logout user",
     * description="Logs out the current authenticated user by invalidating their token.",
     * tags={"Authentication"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Logout successful",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Logged out successfully")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->update([
            'refresh_token' => null,
            'refresh_token_expires_at' => null,
        ]);

        $forgetCookie = cookie()->forget('refreshToken');

        return response()->json(['message' => 'Logged out'])->withCookie($forgetCookie);
    }
}
