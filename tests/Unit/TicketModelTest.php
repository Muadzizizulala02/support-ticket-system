<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Reply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a ticket belongs to a user.
     */
    public function test_ticket_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        // Access the relationship
        $this->assertInstanceOf(User::class, $ticket->user);
        $this->assertEquals($user->id, $ticket->user->id);
    }

    /**
     * Test that a ticket has many replies.
     */
    public function test_ticket_has_many_replies(): void
    {
        $ticket = Ticket::factory()->create();
        $reply1 = Reply::factory()->create(['ticket_id' => $ticket->id]);
        $reply2 = Reply::factory()->create(['ticket_id' => $ticket->id]);

        $this->assertCount(2, $ticket->replies);
        $this->assertInstanceOf(Reply::class, $ticket->replies->first());
    }
}