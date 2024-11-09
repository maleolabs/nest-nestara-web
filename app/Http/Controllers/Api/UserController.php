<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PasswordRequest;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\DomainServices\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    private UserService $userService;

    public function __construct(UserService $userSercvice)
    {
        $this->userService = $userSercvice;
    }
    
    public function updateProfil(UserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $authUser = Auth::guard('api')->user();
        $user = $this->userService->save($authUser->id, $data);
        return response()->json(new UserResource($user, Response::HTTP_OK));
    }

    public function updatePassword(PasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        if (!Hash::check($data['old_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama salah'
            ], Response::HTTP_BAD_REQUEST);
        }
        $this->userService->savePassword($user->id, $data['new_password']);
        return response()->json([
            'success' => true,
            'message' => 'Password di ubah'
        ], Response::HTTP_OK);
    }

}