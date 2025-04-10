<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthRouteTest extends TestCase
{
    /**
     * Test login route
     */
    public function test_login_route_returns_success_response(): void
    {
        $response = $this->get(route('login'));
        
        $response->assertStatus(200);
    }

    /**
     * Test register route
     */
    public function test_register_route_returns_success_response(): void
    {
        $response = $this->get(route('register'));
        
        $response->assertStatus(200);
    }

    /**
     * Test password request route
     */
    public function test_password_request_route_returns_success_response(): void
    {
        $response = $this->get(route('password.request'));
        
        $response->assertStatus(200);
    }

    /**
     * Test password reset route
     */
    public function test_password_reset_route_returns_success_response(): void
    {
        $token = 'test-token';
        $response = $this->get(route('password.reset', ['token' => $token]));
        
        $response->assertStatus(200);
    }
} 