<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TokenExpireException;
use App\Exceptions\TokenMismatchException;
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
        $isValid = false;

        try {
            $isValid = $this->tokenService->verifyToken($token);
            $this->saveTokenException(null);
        } catch (TokenExpireException $e) {
            $this->saveTokenException('TOKEN_EXPIRED');
        } catch (TokenMismatchException $e) {
            $this->saveTokenException('TOKEN_MISMATCH');
        }

        $statusCode = $isValid ? 200 : 400;

        $data = [
            'token' => $token,
            'isValid' => $isValid
        ];

        return response()->json($data, $statusCode);
    }

    private function saveTokenException($exception_type)
    {
        set_pengaturan('CLIENT_TOKEN_INVALID', $exception_type, 'SECURITY');
    }

}