<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Services\DomainServices\UserService;

class UserController extends Controller
{
    
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterUserRequest $request): UserResource
    {
        $data = $request->validated();

        $user = $this->userService->save(null, $data, PEMOHON_ROLE);

        return new UserResource($user);
    }
    
}