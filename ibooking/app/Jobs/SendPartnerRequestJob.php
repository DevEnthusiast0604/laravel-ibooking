<?php

namespace App\Jobs;

use App\Mail\SendEmailPartnerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPartnerRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //For admin
        $email = new SendEmailPartnerRequest($this->details, 'admin');
        $user_admin = get_user_data(get_option('admin_user'));
	    Mail::to($user_admin['email'])->send($email);

	    //For partner
        $email = new SendEmailPartnerRequest($this->details, 'partner');
        Mail::to($this->details['email'])->send($email);
    }
}
