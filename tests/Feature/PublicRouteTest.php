<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicRouteTest extends TestCase
{
    /**
     * Test public home route
     */
    public function test_home_route_returns_success_response(): void
    {
        $response = $this->get(route('home'));
        
        $response->assertStatus(200);
    }
} 