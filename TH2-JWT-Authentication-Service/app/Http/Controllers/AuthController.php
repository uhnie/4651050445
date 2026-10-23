<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'userName' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('UserName', $data['userName'])->first();

        if (!$user || !Hash::check($data['password'], $user->Password)) {
            return response()->json([
                'success' => false,
                'message' => 'Sai userName hoặc password.',
            ], 401);
        }

        try {
            $token = JWTAuth::fromUser($user);

            $user->Token = $token;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công.',
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in_minutes' => (int) env('JWT_TTL', 60),
                'user' => [
                    'idUser' => $user->IdUser,
                    'userName' => $user->UserName,
                ],
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo JWT.',
            ], 500);
        }
    }

    public function validateToken(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'valid' => false,
                'authenticated' => false,
                'message' => 'Thiếu Bearer token.',
            ], 401);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return response()->json([
                    'valid' => false,
                    'authenticated' => false,
                    'message' => 'Token không hợp lệ.',
                ], 401);
            }

            return response()->json([
                'valid' => true,
                'authenticated' => true,
                'message' => 'Token hợp lệ.',
                'user' => [
                    'idUser' => $user->IdUser,
                    'userName' => $user->UserName,
                ],
            ]);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'valid' => false,
                'authenticated' => false,
                'message' => 'Token đã hết hạn.',
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'valid' => false,
                'authenticated' => false,
                'message' => 'Token không hợp lệ.',
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'valid' => false,
                'authenticated' => false,
                'message' => 'Không thể xác thực token.',
            ], 401);
        }
    }
}
