<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\DomainServices\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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

}