<?php

namespace App\Http\Controllers\Api\Client;

use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    use ApiResponse;
    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required|min:6|regex:/[0-9]/|regex:/[a-zA-Z]/',
            'newPassword' => 'required|min:6|regex:/[0-9]/|regex:/[a-zA-Z]/',
        ]);

        if($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $user = $request->user();

        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        if (!Hash::check($request->currentPassword, $user->password)) {
            return $this->errorResponse('Current password is incorrect', 422);
        }

        $user->update([
            'password' => Hash::make($request->newPassword)
        ]);

        return response()->json([
            'message' => 'Password updated successfully'
        ], 200);
    }
}
