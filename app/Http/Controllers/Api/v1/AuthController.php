<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Helpers\ResponseHelper;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error($validator->errors()->toJson(), 400);
        }

        $user = User::create($validator->validated());

        $accessToken = JWTAuth::fromUser($user);

        return response()->json(['access_token' => $accessToken]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ]);

        try {
            if (!$token = JWTAuth::attempt($validator->validate())) {
                return ResponseHelper::error('Unauthorized', 401);
            }

            $user = Auth::user();
            return response()->json(['access_token' => $token]);
        } catch (JWTException $e) {
            return ResponseHelper::error('Could not create token', 500);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json(['access_token' => $token]);
        } catch (JWTException $e) {
            return ResponseHelper::error('Could not create refresh token', 500);
        }
    }
}
