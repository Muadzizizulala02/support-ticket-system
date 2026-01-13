<?php

use App\Http\Controllers\Api\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Route::get('/user', function (Request $request) {
//     //👉 This returns the authenticated User model.
//     // The currently logged-in user As a User object Converted to JSON
//     return $request->user();
//     // only if the requested user authenticate using sanctum
// })->middleware('auth:sanctum');




// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);


// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    // 👍Ticket API routes👍
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
    Route::post('/tickets', [TicketController::class, 'store']);
});

