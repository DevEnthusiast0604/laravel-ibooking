<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailPartnerApproved extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    $user_admin = get_user_data(get_option('admin_user'));
        $email_from = get_option('email_username', $user_admin['email']);
	    $site_name = get_translate(get_option('site_name'));
	    $subject = sprintf(__('[%s] Approved Account'), $site_name);

        return $this->subject($subject)->from($email_from, $site_name)->view('Frontend::emails.partner.partner-approved', [
	        'user' => $this->user,
        ]);
    }
}
