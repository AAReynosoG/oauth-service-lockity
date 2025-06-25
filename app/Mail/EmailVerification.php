<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The verification URL to be sent in the email.
     *
     * @var string
     */
    private $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
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
        return $this->subject('Verification Email')
            ->view('email.email_verification')
            ->with(['url' => $this->url]);
    }
}
