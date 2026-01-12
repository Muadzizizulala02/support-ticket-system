<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;

class TicketTest extends TestCase
{
    // RefreshDatabase ensures:
    // - Migrations run before each test
    // - Database is rolled back after each test
    // - Tests do not affect each other
    use RefreshDatabase;

    /**
     * Test that guests are redirected to login when accessing tickets.
     */
    public function test_guest_cannot_access_tickets(): void
    {
        // Guest tries to access /tickets page
        $response = $this->get('/tickets');

        // Should be redirected to login page
        $response->assertRedirect('/login');
    }

    /**
     * Test that authenticated users can access their tickets page.
     */
    public function test_authenticated_user_can_access_tickets(): void
    {
        // Create a single test user
        /** @var User $user */
        $user = User::factory()->create();

        // Log in as that user and send GET request
        $response = $this->actingAs($user)->get('/tickets');

        // Should successfully see the page
        $response->assertStatus(200);
        $response->assertSee('My Tickets');
    }

    /**
     * Test that a user can create a ticket.
     */
    public function test_user_can_create_ticket(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        // Send POST request to create a ticket
        $response = $this->actingAs($user)->post('/tickets', [
            'title' => 'Test Ticket',
            'description' => 'This is a test description for the ticket.',
        ]);

        // Should redirect to tickets list after creation
        $response->assertRedirect('/tickets');

        // Verify ticket exists in database with correct data
        $this->assertDatabaseHas('tickets', [
            'title' => 'Test Ticket',
            'description' => 'This is a test description for the ticket.',
            'user_id' => $user->id,
            'status' => 'open',
        ]);
    }

    /**
     * Test that admins can view all tickets.
     */
    public function test_admin_can_view_all_tickets(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['is_admin' => true]);

        /** @var User $user */
        $user = User::factory()->create();

        // Create a ticket for a regular user
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        // Admin accesses admin tickets page
        $response = $this->actingAs($admin)->get('/admin/tickets');

        // Should see page successfully
        $response->assertStatus(200);

        // Should see the user's ticket title on page
        $response->assertSee($ticket->title);
    }

    /**
     * Test that ticket validation fails with empty fields.
     */
    public function test_ticket_validation_fails_with_empty_fields(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        // Try to create a ticket with empty data
        $response = $this->actingAs($user)->post('/tickets', [
            'title' => '',
            'description' => '',
        ]);

        // Should have validation errors for title and description
        $response->assertSessionHasErrors(['title', 'description']);

        // Should NOT create any ticket
        $this->assertDatabaseCount('tickets', 0);
    }

    /**
     * Test that title must be at least 5 characters.
     */
    public function test_ticket_title_must_be_at_least_5_characters(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        // Try to create ticket with too-short title
        $response = $this->actingAs($user)->post('/tickets', [
            'title' => 'Bug',  // Only 3 characters
            'description' => 'This is a valid description',
        ]);

        // Should return validation error for title
        $response->assertSessionHasErrors('title');

        // Ticket should NOT exist in database
        $this->assertDatabaseCount('tickets', 0);
    }

    /**
     * Test that users cannot view other users' tickets.
     */
    public function test_user_cannot_view_other_users_ticket(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create();

        /** @var User $user2 */
        $user2 = User::factory()->create();

        // User 1 creates a ticket
        $ticket = Ticket::factory()->create([
            'user_id' => $user1->id,
        ]);

        // User 2 tries to view User 1's ticket
        $response = $this->actingAs($user2)->get("/tickets/{$ticket->id}");

        // Should get 403 Forbidden
        $response->assertStatus(403);
    }

    /**
     * Test that admin can update ticket status.
     */
    public function test_admin_status_can_be_update(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['is_admin' => true]);

        // Admin creates a ticket (or is the owner)
        /** @var Ticket $ticket */
        $ticket = Ticket::factory()->create([
            'user_id' => $admin->id,
        ]);

        // Admin updates ticket status
        $response = $this->actingAs($admin)->patch("/admin/tickets/{$ticket->id}/status", [
            'status' => 'closed',
        ]);

        // Controller redirects after update
        $response->assertStatus(302);

        // Verify ticket status updated in database
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'closed',
        ]);
    }
}
