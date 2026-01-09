<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

// When visited, it shows the welcome.blade.php view
Route::get('/', function () {
    return view('welcome');
});

// When visited, it shows the dashboard.blade.php view
Route::get('/dashboard', function () {
    return view('dashboard');
    // checks if user is logged in
    // If NOT logged in → redirected to /login
    // Contoh nak access dashboard: "http://127.0.0.1:8000/dashboard", dia akan redirect ke login dulu
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    // [TicketController::class, 'create'] - Calls the create() method in TicketController
    // name('tickets.create'); - Names the route 'tickets.create' for easy reference
    // This route shows the form to create a new ticket
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    // This route handles the form submission to store a new ticket
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    // This route displays a list of tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    // This route displays a specific ticket based on its ID
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    // This route updates the status of a specific ticket
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
    // This route allows users to add replies to a specific ticket
    Route::post('/tickets/{ticket}/replies', [TicketController::class, 'storeReply'])->name('tickets.replies.store');

});

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/tickets', [TicketController::class, 'adminIndex'])->name('admin.tickets.index');
    // This route displays a specific ticket based on its ID but use admin method
    Route::get('/admin/tickets/{ticket}', [TicketController::class, 'adminShow'])->name('admin.tickets.show');
    Route::patch('/admin/tickets/{ticket}/status', [TicketController::class, 'adminUpdateStatus'])->name('admin.tickets.update-status');
});

// semua route yang berkaitan dengan authentication (login, register, password reset, email verification, etc.) ada dalam file routes/auth.php
require __DIR__ . '/auth.php';
