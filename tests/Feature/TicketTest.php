<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class TicketTest extends TestCase
{

    // What does it do?
    // Runs migrations before each test
    // Rolls back after each test
    // Each test starts with a fresh database
    // Tests don't affect each other
    use RefreshDatabase;


    /**
     * Test that guests are redirected to login when accessing tickets.
     */
    public function test_guest_cannot_access_tickets(): void
    {
        // Try to access tickets page without being logged in
        // Simulate a GET request to /tickets

        // When this line runs:
        // Laravel creates a fake HTTP request
        // No user session is attached
        // No cookies
        // No auth data
        // ➡️ Laravel assumes: “This user is NOT logged in”
        $response = $this->get('/tickets');

        // Should be redirected to login
        // assert ni macam method untuk check sesuatu condition
        $response->assertRedirect('/login');
    }

    /**
     * Test that authenticated users can access their tickets page.
     */
    public function test_authenticated_user_can_access_tickets(): void
    {
        // Create a test user
        $user = User::factory()->create();

        // Log in as that user
        // user send the request and response captured in $response
        $response = $this->actingAs($user)->get('/tickets');

        // Should see the page successfully
        $response->assertStatus(200);
        $response->assertSee('My Tickets');
    }

    /**
 * Test that a user can create a ticket.
 */
public function test_user_can_create_ticket(): void
{
    // Create and login as a user
    $user = User::factory()->create();

    // Send POST request to create ticket
    $response = $this->actingAs($user)->post('/tickets', [
        'title' => 'Test Ticket',
        'description' => 'This is a test description for the ticket.',
    ]);

    // Should redirect to tickets list
    $response->assertRedirect('/tickets');

    // Check ticket was created in database
    $this->assertDatabaseHas('tickets', [
        'title' => 'Test Ticket',
        'description' => 'This is a test description for the ticket.',
        'user_id' => $user->id,
        'status' => 'open',
    ]);
}
}
