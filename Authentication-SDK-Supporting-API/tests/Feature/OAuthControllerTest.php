<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class OAuthControllerTest extends TestCase
{
    public function testRedirectToProvider()
    {
        $response = $this->get('/oauth/google');

        $response->assertStatus(302);

        $response->assertRedirect();

        $redirectUrl = $response->headers->get('location');

        dd($redirectUrl);
    }

    public function testGenerateTokenWithValidRefreshTokenWithTokenExpired()
    {
        $user = User::where('email', 'shahriarjoyy@gmail.com')->first();

        $this->assertNotNull($user, 'No user found with the given email');

        $response = $this->postJson('/oauth/refresh', [
            'refresh_token' => 'pdCF9I3j',
        ]);

        $response->assertStatus(200);

        dd($response->json());
    }

    public function testGenerateTokenWithInvalidRefreshToken()
    {
        $user = User::where('email', 'shahriarjoyy@gmail.com')->first();

        $this->assertNotNull($user, 'No user found with the given email');

        $response = $this->postJson('/oauth/refresh', [
            'refresh_token' => 'pdCF9I3j',
        ]);

        $response->assertStatus(401);

        dd($response->json());
    }

    public function testGenerateTokenWithValidRefreshTokenWithTokenNotExpired()
    {
        $user = User::where('email', 'shahriarjoyy@gmail.com')->first();

        $this->assertNotNull($user, 'No user found with the given email');

        $response = $this->postJson('/oauth/refresh', [
            'refresh_token' => 'yuiNP1QL',
        ]);

        $response->assertStatus(400);

        dd($response->json());
    }

}
