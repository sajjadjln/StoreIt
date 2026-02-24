<?php

namespace Tests\Traits;

use App\Contracts\TokenManagerContract;
use App\Services\AuthService;
use Mockery;
use Mockery\MockInterface;


trait MocksAuthService
{
    /** @var TokenManagerContract&MockInterface */
    protected TokenManagerContract $tokenManagerMock;
    protected AuthService $authService;
    protected function setupAuthService(): void
    {
        $this->tokenManagerMock = Mockery::mock(TokenManagerContract::class);
        $this->authService = new AuthService($this->tokenManagerMock);
    }
}
