<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_users_cannot_view_customers(): void
    {
        $response = $this->get('/customers');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_users_can_view_customer_list(): void
    {
        Customer::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/customers');

        $response->assertStatus(200);
        $response->assertViewHas('customers');
    }

    public function test_authenticated_users_can_create_a_customer(): void
    {
        $customerData = [
            'name' => 'John Doe',
            'customer_id' => 'CUST-100',
            'email' => 'john@example.com',
            'mobile' => '9876543210',
            'address' => '123 Street, City',
            'area' => 'Route A',
            'start_date' => '2026-07-01',
            'status' => 'Active',
        ];

        $response = $this->actingAs($this->user)->post('/customers', $customerData);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'email' => 'john@example.com',
        ]);
    }
}
