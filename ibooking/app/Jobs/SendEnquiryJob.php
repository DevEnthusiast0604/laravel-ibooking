<?php

namespace App\Jobs;

use App\Mail\SendEmailEnquiry;
use App\Mail\SendEmailOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEnquiryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $enquiry;
    protected $postObject;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($enquiry, $postObject)
    {
        $this->enquiry = $enquiry;
        $this->postObject = $postObject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        //For admin
        $user_admin = get_user_data(get_option('admin_user'));
        $email = new SendEmailEnquiry($this->enquiry, $this->postObject, 'admin', $user_admin['id']);
        Mail::to($user_admin['email'])->send($email);

        //For partner
        $user_partner = get_user_data($this->postObject['author']);
        $email = new SendEmailEnquiry($this->enquiry, $this->postObject, 'partner', $user_partner['id']);
        Mail::to($user_partner['email'])->send($email);
    }
}
