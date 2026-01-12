<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that ticket owner can add a reply.
     */
    public function test_ticket_owner_can_add_reply(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Ticket $ticket */
        $ticket = Ticket::factory()->create([
            'user_id' => $user->id,
        ]);

        // Owner posts a reply
        $response = $this->actingAs($user)->post("/tickets/{$ticket->id}/replies", [
            'message' => 'This is my reply to the ticket',
        ]);

        // Should redirect back to ticket page
        $response->assertRedirect("/tickets/{$ticket->id}");

        // Verify reply exists in database
        $this->assertDatabaseHas('replies', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => 'This is my reply to the ticket',
        ]);
    }

    /**
     * Test that admin can reply to any ticket.
     */
    public function test_admin_can_reply_to_any_ticket(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['is_admin' => true]);

        /** @var User $user */
        $user = User::factory()->create();

        /** @var Ticket $ticket */
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        // Admin posts a reply to the user's ticket
        $response = $this->actingAs($admin)->post("/tickets/{$ticket->id}/replies", [
            'message' => 'Admin response here',
        ]);

        // Should redirect back to ticket page
        $response->assertRedirect("/tickets/{$ticket->id}");

        // Verify reply exists in database
        $this->assertDatabaseHas('replies', [
            'ticket_id' => $ticket->id,
            'user_id' => $admin->id,
            'message' => 'Admin response here',
        ]);
    }

    /**
     * Test that unauthorized users cannot reply to tickets.
     */
    public function test_unauthorized_user_cannot_reply_to_ticket(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create();

        /** @var User $user2 */
        $user2 = User::factory()->create();

        /** @var Ticket $ticket */
        $ticket = Ticket::factory()->create([
            'user_id' => $user1->id,
        ]);

        // User 2 tries to post a reply to User 1's ticket
        $response = $this->actingAs($user2)->post("/tickets/{$ticket->id}/replies", [
            'message' => 'Unauthorized reply',
        ]);

        // Should get 403 Forbidden
        $response->assertStatus(403);

        // Verify no reply was created
        $this->assertDatabaseCount('replies', 0);
    }
}
