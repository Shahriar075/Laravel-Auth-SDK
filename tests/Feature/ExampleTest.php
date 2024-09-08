<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use AuthSDKLibrary\AuthManager;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected $authManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authManager = new AuthManager();
    }
}
