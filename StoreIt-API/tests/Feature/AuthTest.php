<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private function createUser($overrides = [])
    {
        $defaults = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ];

        return User::factory()->create(array_merge($defaults, $overrides));
    }
    public function test_user_can_register(): void
    {
        $data = [
            "username" => "sajjaddsdd",
            "email" => "sajjadjldm1sd999@gmail.com",
            "password" => "sajjadjlsnd"
        ];

        $response = $this->post('api/auth/signup', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    "id",
                    "username",
                    "email",
                    "quota_bytes",
                    "used_bytes",
                    "available_bytes",
                    "created_at",
                    "updated_at"
                ],
                'token'
            ]);

        $this->assertDatabaseHas('users', [
            "username" => "sajjaddsdd",
            "email" => "sajjadjldm1sd999@gmail.com",
        ]);
        $user = User::where('email', 'sajjadjldm1sd999@gmail.com')->first();
        $this->assertTrue(Hash::check('sajjadjlsnd', $user->password));
    }

    public function test_registration_requires_all_fields()
    {
        $response = $this->postJson('api/auth/signup', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'email', 'password']);
    }

    public function test_registaration_requires_valid_email()
    {
        $invalidEmails = [
            'plainaddress',
            'missing@domain',
            '@missingusername.com',
            'user@.com',
            'user@domain.',
            'user@domain.c',
            'user name@domain.com',
            'user@domain..com',
            'user@domain.corporate',
            'user@-domain.com',
            'user@domain-.com',
        ];

        foreach ($invalidEmails as $invalidEmail) {
            $response = $this->postJson('api/auth/signup', [
                "username" => "sajjaddsdd",
                "email" => $invalidEmail,
                "password" => "sajjadjlsnd"
            ]);

            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['email']);

        }
    }

    public function test_registeration_requires_unique_user()
    {
        $data = [
            "username" => "sajjaddsdd",
            "email" => $this->faker->unique()->email(),
            "password" => "sajjadjlsnd"
        ];


        $this->postJson('/api/auth/signup', $data)
            ->assertStatus(201);


        $response = $this->postJson('/api/auth/signup', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_registration_requires_unique_username()
    {
        $data1 = [
            "username" => "sameuser",
            "email" => $this->faker->unique()->email(),
            "password" => "sajjadjlsnd"
        ];

        $data2 = [
            "username" => "sameuser",
            "email" => $this->faker->unique()->email(),
            "password" => "password456"
        ];

        $this->postJson('/api/auth/signup', $data1)
            ->assertStatus(201);

        $response = $this->postJson('/api/auth/signup', $data2);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    public function test_registeration_dont_accept_short_password()
    {
        $data = [
            "username" => "sameuser",
            "email" => $this->faker->unique()->email(),
            "password" => "sajjad"
        ];

        $this->postJson('/api/auth/signup', $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_login()
    {

        $this->createUser();

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    "id",
                    "username",
                    "email",
                    "quota_bytes",
                    "used_bytes",
                    "available_bytes",
                    "created_at",
                    "updated_at"
                ],
                'token'
            ]);
    }
}
