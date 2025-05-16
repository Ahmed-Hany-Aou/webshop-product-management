<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Mail; 

class AuthControllerTest extends TestCase
{
       use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake(); // Fake emails for all tests in this class
    }

    /** @test */
    public function it_allows_a_user_to_register()
    {
        $response = $this->postJson("/api/register", [
            "name" => "Test User",
            "email" => "test@example.com",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $response->assertStatus(201) // Changed from 200 to 201
                 ->assertJsonStructure([
                     "token",         // Changed from access_token
                     "message",       // Added message key
                 ])
                 ->assertJsonFragment(["message" => "User created successfully"]);

        $this->assertDatabaseHas("users", [
            "email" => "test@example.com",
        ]);
    }

    /** @test */
    public function it_requires_valid_credentials_to_login()
    {
        $user = User::factory()->create([
            "password" => bcrypt("password"),
        ]);

        $response = $this->postJson("/api/login", [
            "email" => $user->email,
            "password" => "password",
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     "token",        // Changed from access_token
                     "user" => [     // Added user structure
                        "id",
                        "name",
                        "email",
                        // Add other fields you expect in the user object
                     ]
                 ]);
    }

    /** @test */
    public function it_rejects_invalid_login_credentials()
    {
        $user = User::factory()->create([
            "password" => bcrypt("password"),
        ]);

        $response = $this->postJson("/api/login", [
            "email" => $user->email,
            "password" => "wrongpassword",
        ]);

        $response->assertStatus(401) // Changed from 422 to 401
                 ->assertJson(["message" => "Invalid credentials"]); // Changed assertion
    }

    /** @test */
    public function it_requires_email_and_password_for_login()
    {
        $response = $this->postJson("/api/login", []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(["email", "password"]);
    }

    /** @test */
    public function it_requires_email_and_password_for_registration()
    {
        $response = $this->postJson("/api/register", []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(["name", "email", "password"]);
    }

    /** @test */
    public function it_rejects_duplicate_email_registration()
    {
        User::factory()->create([
            "email" => "test@example.com",
        ]);

        $response = $this->postJson("/api/register", [
            "name" => "Test User",
            "email" => "test@example.com",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(["email"]);
    }

    /** @test */
    public function it_allows_a_user_to_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken("auth_token")->plainTextToken;

        $response = $this->postJson("/api/logout", [], [
            "Authorization" => "Bearer " . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     "message" => "Logged out successfully",
                 ]);
    }

    /** @test */
    public function it_rejects_logout_without_authentication()
    {
        $response = $this->postJson("/api/logout");

        $response->assertStatus(401); // Standard unauthenticated response for Sanctum
                 // The default message for 401 from Sanctum might be just {"message": "Unauthenticated."} 
                 // or it might be handled by an exception handler. 
                 // If the default Laravel handler is used, it will be {"message": "Unauthenticated."}
        $response->assertJsonFragment(["message" => "Unauthenticated."]);
    }
}

