<?php

// app/Mail/WelcomeEmail.php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    // Inject the user data
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // Build the message
    public function build()
    {
        return $this->subject('Welcome to Our Application!')
                    ->view('emails.welcome') // Make sure to create a view for this (we'll do that next)
                    ->with([
                        'userName' => $this->user->name,
                    ]);
    }
}
