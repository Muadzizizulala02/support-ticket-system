<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Reply;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class TicketController extends Controller
{
    // our view form to create ticket
    public function create()
    {
        // this will return the view located at resources/views/tickets/create.blade.php
        return view('tickets.create');
    }

    // store the ticket in the database
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Create the ticket
        Ticket::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'open',
        ]);

        // Redirect with success message
        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully!');
    }

    public function index()
    {
        // Retrieve tickets for the authenticated user
        $tickets = Auth::user()
            ->tickets()
            ->latest()
            ->get();
        // Return the view with tickets data
        return view('tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        // Ensure the user owns the ticket
        // If the current user did not create this ticket AND the current user is not an administrator
        if ($ticket->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized actionnn.');
        }

        // Return the view with the ticket data
        return view('tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        // Ensure the user ja yg boleh update the ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the new status
        $validated = $request->validate([
            'status' => 'required|in:open,closed,in_progress',
        ]);

        // Update the ticket status
        $ticket->update([
            'status' => $validated['status'],
        ]);

        // Redirect back with success message
        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket status updated successfully!');
    }


    // Start admin methods

    /**
     * Display all tickets (Admin only).
     */
    public function adminIndex()
    {
        // Get ALL tickets (not just user's own)
        // dapatkan juga user info dengan eager loading
        // supaya dalam view kita boleh tunjukkan nama user yang create ticket tu
        // dan elakkan N+1 query problem
        // contoh N+1 query problem: kalau ada 100 tickets, dan kita nak tunjuk nama user untuk setiap ticket,
        // tanpa eager loading, kita akan buat 1 query untuk dapatkan semua tickets,
        // then 100 queries lagi untuk dapatkan user info untuk setiap ticket
        // total 101 queries
        // dengan eager loading, kita cuma buat 2 queries je:
        // 1 untuk dapatkan semua tickets
        // 1 lagi untuk dapatkan semua user yang berkaitan dengan tickets tu
        $tickets = Ticket::with('user')->latest()->get();

        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Update any ticket's status (Admin only).
     */
    public function adminUpdateStatus(Request $request, Ticket $ticket)
    {
        // Admin can update any ticket, so no ownership check!

        // Validate the status
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        // Update the ticket status
        $ticket->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket status updated successfully!');
    }

    public function adminShow(Ticket $ticket)
    {

        // Return the view with the ticket data
        return view('tickets.show', compact('ticket'));
    }

    // End admin methods


    // Start reply methods
    /**
     * Store a reply for a ticket.
     */
    public function storeReply(Request $request, Ticket $ticket)
    {
        // // Check if user can reply to this ticket
        // // Either the ticket owner OR an admin

        if ($ticket->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the message
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Create the reply
        Reply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Reply added successfully!');
    }


    // End reply methods

}
