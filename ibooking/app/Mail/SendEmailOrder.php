<?php

namespace App\Mail;

use App\Jobs\SendOrderJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailOrder extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;
    protected $role;
    protected $uid;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $role, $uid)
    {
        $this->order = $order;
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
        $post_type = $this->order['post_type'];

        $site_name = get_translate(get_option('site_name'));
        $subject =  sprintf(__('[%s] %s Booking Request'), $site_name, ucfirst($post_type));
        $user_admin = get_user_data(get_option('admin_user'));
        $email_from = get_option('email_username', $user_admin['email']);

        return $this->subject($subject)->from($email_from, $site_name)->view('Frontend::emails.'. $this->role .'.'. $post_type .'-order', [
            'user_id' => $this->uid,
            'order' => $this->order,
        ]);
    }
}
