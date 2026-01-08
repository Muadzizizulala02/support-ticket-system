<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

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
}
