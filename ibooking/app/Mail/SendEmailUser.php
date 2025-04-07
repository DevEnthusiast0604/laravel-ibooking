<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailUser extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $role;
    protected $uid;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $role, $uid)
    {
        $this->user = $user;
        $this->role = $role;
        $this->uid = $uid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $site_name = get_translate(get_option('site_name'));
        $subject = sprintf(__('[%s] User Registration'), $site_name);
        $user_admin = get_user_data(get_option('admin_user'));
        $email_from = get_option('email_username', $user_admin['email']);
        return $this->subject($subject)->from($email_from, $site_name)->view('Frontend::emails.'. $this->role .'.' .'new-user', [
            'user_id' => !empty($this->uid) ? $this->uid : '',
            'post_data' => $this->user,
        ]);
    }
}
