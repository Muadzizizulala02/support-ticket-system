<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;


class AuthController extends Controller
{
    /**
     * Login user and return API token.
     */
    public function login(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists and password is correct
        // Error needs Accept: application/json because at postman
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        //Delete previous tokens
        $user->tokens()->delete();

        // Create token
        $token = $user->createToken('api-token')->plainTextToken;

        // Return success response with token
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
            ]
        ]);
    }

    /**
     * Logout user (revoke token).
     */
    public function logout(Request $request)
    {
        // Delete the current token
        /** @var PersonalAccessToken|null $token */
        $token = $request->user()->currentAccessToken();

        if ($token) {
        $token->delete();
    }

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }
}