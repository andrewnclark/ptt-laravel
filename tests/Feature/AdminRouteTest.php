<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AdminRouteTest extends TestCase
{
    /**
     * Test admin dashboard route
     */
    public function test_admin_dashboard_route_returns_success_response(): void
    {
        $response = $this->get(route('admin.dashboard'));
        
        $response->assertStatus(200);
    }

    /**
     * Test companies index route
     */
    public function test_companies_index_route_returns_success_response(): void
    {
        $response = $this->get(route('admin.companies.index'));
        
        $response->assertStatus(200);
    }

    /**
     * Test companies create route
     */
    public function test_companies_create_route_returns_success_response(): void
    {
        $response = $this->get(route('admin.companies.create'));
        
        $response->assertStatus(200);
    }

    /**
     * Test companies show route
     */
    public function test_companies_show_route_returns_success_response(): void
    {
        // This route requires a company ID parameter
        $response = $this->get(route('admin.companies.show', ['company' => 1]));
        
        $response->assertStatus(200);
    }

    /**
     * Test companies edit route
     */
    public function test_companies_edit_route_returns_success_response(): void
    {
        // This route requires a company ID parameter
        $response = $this->get(route('admin.companies.edit', ['company' => 1]));
        
        $response->assertStatus(200);
    }

    /**
     * Future test when authentication is required for admin routes
     * Commented out for now until auth middleware is implemented
     */
    /*
    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->get(route('admin.dashboard'));
        
        $response->assertStatus(200);
    }
    */
} 