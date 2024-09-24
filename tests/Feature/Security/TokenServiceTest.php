<?php

namespace Tests\Feature\Security;

use App\Services\Security\TokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class TokenServiceTest extends TestCase
{
    
    public function test_generate_token(): void
    {
        $tokenService = app(TokenService::class);
        
        $ftoken = $tokenService->generateToken();
        $stoken = $tokenService->generateToken();
        assertSame($ftoken, $stoken);

        $token = $tokenService->getToken();
        assertTrue($tokenService->verifyToken($token));
    }
}
