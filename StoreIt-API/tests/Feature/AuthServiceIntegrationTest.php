<?php

namespace Tests\Feature;

use App\DTOs\SignupCredentials;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;
use App\Contracts\TokenManagerContract;
use App\Services\AuthService;

class AuthServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private $tokenManagerMock;
    private $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenManagerMock = Mockery::mock(TokenManagerContract::class);
        $this->authService = new AuthService($this->tokenManagerMock);
    }

    public function test_signup_stores_user_in_database_with_correct_data()
    {
        $data = new SignupCredentials(
            username: 'integration_user',
            email: 'integration@test.com',
            password: 'password123',
            quota_bytes: 1024
        );
        $this->tokenManagerMock->shouldReceive('generate')
            ->once()
            ->andReturn('fake-integration-token');

        $result = $this->authService->signup($data);

        $this->assertEquals('integration_user', $result->user->username);
        $this->assertEquals('fake-integration-token', $result->token);
        $this->assertDatabaseHas('users', [
            'username' => 'integration_user',
            'email' => 'integration@test.com',
            'quota_bytes' => 1024,
            'used_bytes' => 0
        ]);
    }

    public function test_signup_uses_default_quota_when_none_provided()
    {
        $data = new SignupCredentials(
            username: 'default_user',
            email: 'default@test.com',
            password: 'password123',
            quota_bytes: null
        );

        $this->tokenManagerMock->shouldReceive('generate')->andReturn('token');

        $this->authService->signup($data);

        $this->assertDatabaseHas('users', [
            'email' => 'default@test.com',
            'quota_bytes' => config('app.storage.min_quota')
        ]);
    }
}
