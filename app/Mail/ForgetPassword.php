<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * URL to be sent in the email.
     *
     * @var string
     */
    private $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * This method sets the subject of the email and loads the email view,
     * passing the URL parameter to the view.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Lockity - Password Recovery')
            ->view('email.forget_password')
            ->with(['token' => $this->token]);
    }
}
