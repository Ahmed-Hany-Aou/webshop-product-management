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
$response->assertJsonStructure([
    "status_code",
    "message",
    "result" => ["token"], // Now correctly expecting the token inside the result object
]);

    }

    /** @test */
   public function it_requires_valid_credentials_to_login()
{
    // Create a user with a password
    $user = User::factory()->create([
        "password" => bcrypt("password"),
    ]);

    // Make a POST request to the login API with valid credentials
    $response = $this->postJson("/api/login", [
        "email" => $user->email,
        "password" => "password",
    ]);

    // Assert the response contains the expected structure
    $response->assertStatus(200)
             ->assertJsonStructure([
                 "status_code",  // Check for status_code in the response
                 "message",      // Check for message in the response
                 "result" => [   // Check for result object
                     "token",    // Check for token inside result
                     "user" => [ // Check for user details inside result
                         "id",
                         "name",
                         "email",
                     ]
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

    // Assert the structure of the response to match the standardized format
    $response->assertStatus(401)
             ->assertJsonStructure([
                 "status_code",  // Check for status_code field
                 "message",      // Check for message field
             ])
             ->assertJson([
                 "message" => "Invalid credentials", // Check the message content
             ]);
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

