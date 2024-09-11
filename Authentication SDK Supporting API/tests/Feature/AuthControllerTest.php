<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserToken;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testRegisterUser()
    {
        $response = $this->postJson('/register', [
            'name' => 'Noyon',
            'email' => 'noyon@gmail.com',
            'password' => 'password',
            'role_id' => 1,
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure(['user']);

        dd($response->json());
    }

    public function testLoginUser()
    {
        $response = $this->postJson('/login', [
            'email' => 'ashik@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure(['token']);

        dd($response->json());
    }

    public function testAddUserRole()
    {
        $response = $this->postJson('/add-user-role', [
            'name' => 'New role'
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure(['message']);

        dd($response->json());
    }

    public function testAddUserRoleWithDuplicateName()
    {
        $response = $this->postJson('/add-user-role', [
            'name' => 'New'
        ]);

        $response->assertStatus(500);

        $response->assertJsonStructure(['error']);

        dd($response->json());
    }

    public function testGenerateTokenWithValidRefreshTokenWithTokenExpired()
    {
        $user = User::where('email', 'abid@gmail.com')->first();

        $this->assertNotNull($user, 'No user found with the given email');

        $response = $this->postJson('/auth/refresh', [
                'refresh_token' => '7CjVUGSF',
        ]);

        $response->assertStatus(200);

        dd($response->json());
    }

    public function testGenerateTokenWithInvalidRefreshToken()
    {
        $user = User::where('email', 'abid@gmail.com')->first();

        $this->assertNotNull($user, 'No user found with the given email');

        $response = $this->postJson('/auth/refresh', [
            'refresh_token' => 'otuXzL',
        ]);

        $response->assertStatus(401);

        dd($response->json());
    }

    public function testGenerateTokenWithValidRefreshTokenWithTokenNotExpired()
    {
        $user = User::where('email', 'abid@gmail.com')->first();

        $this->assertNotNull($user, 'No user found with the given email');

        $response = $this->postJson('/auth/refresh', [
            'refresh_token' => '7CjVUGSF',
        ]);

        $response->assertStatus(400);

        dd($response->json());
    }

}
