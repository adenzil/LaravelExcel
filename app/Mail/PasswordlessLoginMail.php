<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class PasswordlessLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $options;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $options = [])
    {
        $this->user = $user;
        $this->options = $options;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('your magic link is')->view('email.passwordlessLogin')->with([
            'link' => $this->buildLink()
        ]);
    }

    protected function buildLink()
    {
        return url('/login/passwordless/'.$this->user->token->token.'?'.http_build_query($this->options));
    }
}
