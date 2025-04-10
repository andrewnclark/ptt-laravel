<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouteStatusTest extends TestCase
{
    /**
     * Test the home route status.
     */
    public function test_home_route_status(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test the admin dashboard route status.
     */
    public function test_admin_dashboard_route_status(): void
    {
        $response = $this->get('/admin');
        $response->assertStatus(200);
    }

    /**
     * Test the admin companies index route status.
     */
    public function test_admin_companies_index_route_status(): void
    {
        $response = $this->get('/admin/companies');
        $response->assertStatus(200);
    }

    /**
     * Test the admin companies create route status.
     */
    public function test_admin_companies_create_route_status(): void
    {
        $response = $this->get('/admin/companies/create');
        $response->assertStatus(200);
    }

    /**
     * Test the admin companies show route status.
     */
    public function test_admin_companies_show_route_status(): void
    {
        // This route requires a company ID parameter
        $response = $this->get('/admin/companies/1');
        $response->assertStatus(200);
    }

    /**
     * Test the admin companies edit route status.
     */
    public function test_admin_companies_edit_route_status(): void
    {
        // This route requires a company ID parameter
        $response = $this->get('/admin/companies/1/edit');
        $response->assertStatus(200);
    }

    /**
     * Test the login route status.
     */
    public function test_login_route_status(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test the register route status.
     */
    public function test_register_route_status(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * Test the password request route status.
     */
    public function test_password_request_route_status(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }
} 