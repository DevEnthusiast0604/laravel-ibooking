<?php

namespace App\Jobs;

use App\Mail\SendEmailOrder;
use App\Mail\SendEmailUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //For admin
        $user_admin = get_user_data(get_option('admin_user'));
        $email = new SendEmailUser($this->user, 'admin', $user_admin['id']);
        Mail::to($user_admin['email'])->send($email);

        //For user
        $user_data = $this->user;
        $email = new SendEmailUser($this->user, 'customer', '');
        Mail::to($user_data['email'])->send($email);
    }
}
