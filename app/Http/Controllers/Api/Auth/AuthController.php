<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Auth\AuthResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuthController extends Controller
{
    use ApiResponse, HasApiTokens, HasFactory, Notifiable;

    public function login (Request $request) {
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

        $token = $user->createToken('token')->plainTextToken;

        $content = [
            'user'=> $user,
            'token' => $token
        ];

        return $this->successResponse('Login success',new AuthResource($content),200);
    }
}
