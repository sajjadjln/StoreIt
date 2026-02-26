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

    public function test_user_can_register(): void
    {
        $data = [
            "username" => $this->faker->userName(),
            "email" => $this->faker->email(),
            "password" => $this->faker->password(8,20)
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
            "username" => $data['username'],
            "email" => $data['email'],
        ]);
        $user = User::where('email', $data['email'])->first();
        $this->assertTrue(Hash::check($data['password'], $user->password));
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
                "username" => $this->faker->userName(),
                "email" => $invalidEmail,
                "password" => $this->faker->password(8,20)
            ]);

            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['email']);

        }
    }

    public function test_registeration_requires_unique_user()
    {
        $data = [
            "username" => $this->faker->userName(),
            "email" => $this->faker->unique()->email(),
            "password" => $this->faker->password(8,20)
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
            "password" => $this->faker->password(8,20)
        ];

        $data2 = [
            "username" => "sameuser",
            "email" => $this->faker->unique()->email(),
            "password" => $this->faker->password(8,20)
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

        $data = [
            'email' => $this->faker->email(),
            'password' => $this->faker->password(8,20)
        ];
        User::factory()->create($data);

        $response = $this->postJson('/api/auth/login', $data);
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
