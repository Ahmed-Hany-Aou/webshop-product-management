<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        // Use the built-in $request->validate() method for validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',  // `password_confirmation` field must match `password`
        ]);

        // Check if the email is already registered (this is extra, since validate already checks this)
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json(['message' => 'Email is already registered'], 409);
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create API token for the user
        $token = $user->createToken('YourAppName')->plainTextToken;

        // Send a welcome email
        Mail::to($user->email)->send(new WelcomeEmail($user));
        

        return response()->json(['token' => $token, 'message' => 'User created successfully'], 201);
    }

    // Login user
    public function login(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Create token
        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    // Get authenticated user
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // Logout user
    public function logout(Request $request)
    {
        // Delete all user's tokens
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
