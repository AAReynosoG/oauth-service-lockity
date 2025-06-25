<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MfaCode extends Mailable
{
    use Queueable, SerializesModels;
    private $code;
    private $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code, $email)
    {
        $this->code = $code;
        $this->email = $email;
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
        return $this->subject('Sign Up Code')
            ->view('email.mfa')
            ->with(['code' => $this->code, 'email' => $this->email]);
    }
}
