<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailPartnerRequest extends Mailable
{
    use Queueable, SerializesModels;
    protected $details;
    protected $role;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $role)
    {
        $this->details = $details;
        $this->role = $role;
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
	    $subject = sprintf(__('[%s] Partner Registration'), $site_name);

	    if($this->role == 'admin'){
	        $name = get_user_name($user_admin['id']);
        }else{
            $name = $this->details['first_name'] . ' ' . $this->details['last_name'];
        }

        return $this->subject($subject)->from($email_from, $site_name)->view('Frontend::emails.'. $this->role .'.partner-registration', [
	        'name' => $name,
	        'post_data' => $this->details,
        ]);
    }
}
