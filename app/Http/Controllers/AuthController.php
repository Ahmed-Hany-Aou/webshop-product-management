<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\User;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 */
class AuthController extends Controller
{
    /**
     * Register a new user
     * 
     * @OA\Post(
     *     path="/register",
     *     operationId="register",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Creates a new user account and returns an authentication token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe", description="User's full name"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com", description="User's email address"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123", description="User's password (min 8 characters)"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="Password123", description="Confirmation of user's password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="2|laravel_sanctum_X9WyR7SePZAdskjVasd...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Email already registered",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=409),
     *             @OA\Property(property="message", type="string", example="Email is already registered"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=422),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 @OA\Property(
     *                     property="errors",
     *                     type="object",
     *                     @OA\Property(
     *                         property="email",
     *                         type="array",
     *                         @OA\Items(type="string", example="The email has already been taken.")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
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
            return ApiResponse::error('Email is already registered', 409);
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
        
        return ApiResponse::success(['token' => $token], 'User created successfully', 201);
    }

    /**
     * Login user
     * 
     * @OA\Post(
     *     path="/login",
     *     operationId="login",
     *     tags={"Authentication"},
     *     summary="Login a user",
     *     description="Authenticates a user and returns a token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com", description="User's email address"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123", description="User's password (min 8 characters)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="2|laravel_sanctum_X9WyR7SePZAdskjVasd..."),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="user@example.com"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Invalid credentials"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=422),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 @OA\Property(
     *                     property="errors",
     *                     type="object",
     *                     @OA\Property(
     *                         property="email",
     *                         type="array",
     *                         @OA\Items(type="string", example="The email field is required.")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
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
            return ApiResponse::error('Invalid credentials', 401);
        }

        // Create token
        $token = $user->createToken('YourAppName')->plainTextToken;

        return ApiResponse::success(['token' => $token, 'user' => $user], 'Login successful');
    }

    /**
     * Get authenticated user
     * 
     * @OA\Get(
     *     path="/user",
     *     operationId="getUser",
     *     tags={"Authentication"},
     *     summary="Get authenticated user",
     *     description="Returns the currently authenticated user's details",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="User details retrieved successfully"),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function user(Request $request)
    {
        return ApiResponse::success($request->user(), 'User details retrieved successfully');
    }

    /**
     * Logout user
     * 
     * @OA\Post(
     *     path="/logout",
     *     operationId="logout",
     *     tags={"Authentication"},
     *     summary="Logout user",
     *     description="Invalidates the user's authentication token",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Logged out successfully"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="result", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        // Delete all user's tokens
        $request->user()->tokens()->delete();

        return ApiResponse::success(null, 'Logged out successfully');
    }
}
