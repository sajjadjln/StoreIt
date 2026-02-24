<?php

namespace Tests\Unit;

use App\Contracts\TokenManagerContract;
use App\DTOs\AuthResponse;
use App\DTOs\LoginCredentials;
use App\DTOs\SignupCredentials;
use App\Services\AuthService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Mockery;
use PHPUnit\Framework\TestCase;
use App\Models\User;
class AuthServiceTest extends TestCase
{
    private $tokenManagerMock;
    private $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tokenManagerMock = Mockery::mock(TokenManagerContract::class);

        $this->authService = new AuthService($this->tokenManagerMock);

    }

    public function test_login_throws_exception_on_invalid_credentials()
    {
        $credentials = new LoginCredentials('test@example.com', 'wrongpassword');

        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'wrongpassword'])
            ->andReturn(false);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('invalid email or password');

        $this->authService->login($credentials);
    }

    public function test_login_returns_auth_response_on_success()
    {
        $credentials = new LoginCredentials('test@example.com', 'correctpassword');
        $fakeUser = new User();
        $fakeUser->id = 1;
        $fakeUser->email = 'test@example.com';


        Auth::shouldReceive('attempt')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn($fakeUser);

        $this->tokenManagerMock
            ->shouldReceive('generate')
            ->once()
            ->with($fakeUser)
            ->andReturn('super-secret-fake-token');

        $response = $this->authService->login($credentials);

        $this->assertInstanceOf(AuthResponse::class, $response);
        $this->assertEquals('super-secret-fake-token', $response->token);
        $this->assertEquals($fakeUser, $response->user);
    }
}
