<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Security\TokenService;
use Illuminate\Http\Request;

class TokenController extends Controller
{

    private TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }
    
    public function verifyClientToken(Request $request)
    {
        $token = $request->token;
        $isValid = $this->tokenService->verifyToken($token);
        $statusCode = $isValid ? 200 : 400;

        $data = [
            'token' => $token,
            'isValid' => $isValid
        ];

        return response()->json($data, $statusCode);
    }

}