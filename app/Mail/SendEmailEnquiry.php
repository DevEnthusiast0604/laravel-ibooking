<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailEnquiry extends Mailable
{
    use Queueable, SerializesModels;
    protected $postObject;
    protected $enquiry;
    protected $role;
    protected $uid;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($enquiry, $postObject, $role, $uid)
    {
        $this->enquiry = $enquiry;
        $this->postObject = $postObject;
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
        $postType =  $this->enquiry['post_type'];
        $site_name = get_translate(get_option('site_name'));
        $subject = sprintf(__('[%s] %s Booking Enquiry'), $site_name, ucfirst($postType));
        $user_admin = get_user_data(get_option('admin_user'));
        $email_from = get_option('email_username', $user_admin['email']);
        return $this->subject($subject)->from($email_from, $site_name)->view('Frontend::emails.'. $this->role .'.'. $postType .'-enquiry', [
            'user_id' => $this->uid,
            'post' => $this->postObject,
            'post_data' => $this->enquiry,
        ]);
    }
}
