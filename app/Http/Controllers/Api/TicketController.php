<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Gets ALL tickets from database
        // Returns a collection of Ticket models
        // $tickets = Ticket::all();

        // Get only the authenticated user's tickets
        $tickets = $request->user()->tickets;


        // Helper function to return JSON
        // Converts arrays/objects to JSON
        // 👉 This means your app is returning data, not a Blade view.
        return response()->json([
            'success' => true,
            // This puts the tickets you fetched into the response
            // Laravel automatically: Converts the $tickets collection into JSON
            'data' => $tickets
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // Request $request is Laravel giving your controller all the data that came from the client.
    public function store(Request $request)
    {
        // Validate the data that we want to create
        // Works because of auth:sanctum middleware
        // Sanctum validates the token and loads the user
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'description' => 'required|string|min:10|max:5000',
        ]);


        // Create the ticket
        $ticket = Ticket::create([
            // Returns the User model of the authenticated user
            'user_id' => $request->user()->id, // Get only the authenticated user's tickets
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket created successfully',
            'data' => $ticket
        ], 201);
    }

    /**
     * Display the specified resource.
     */

    // Where does $id come from?
    // 👉 From the route definition, not from the controller itself.
    // Route pass the data to this function
    public function show(string $id)
    {
        // just get the specific item for the id
        $ticket = Ticket::find($id);

        // check if ticket exist
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'Saja nak bagitau' => 'Ticket not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => $ticket
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
