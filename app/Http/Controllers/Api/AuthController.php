<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Services\DomainServices\UserService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'username atau password salah'], 401);
        }

        $current_user = Auth::guard('api')->user();
        if (!$current_user->hasRole('pemohon')) {
            throw new HttpResponseException(response([
                'errors' => 'login khusus pemohon!'
            ], 403));
        }

        return $this->respondWithToken($token);
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->save(null, $data, PEMOHON_ROLE);

        $token = Auth::guard('api')->login($user);

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(new UserResource(Auth::guard('api')->user()));
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out!']);
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(Auth::guard('api')->refresh());
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'profile_complete' => $this->profileComplete(),
        ]);
    }

    /**
     * Profil dianggap lengkap ketika user sudah memiliki data pemohon.
     * Menggunakan exists() pada relasi agar tetap murah (single query).
     */
    private function profileComplete(): bool
    {
        $user = Auth::guard('api')->user();

        return (bool) $user?->pemohon()->exists();
    }

}