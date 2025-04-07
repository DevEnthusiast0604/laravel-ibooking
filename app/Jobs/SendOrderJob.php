<?php

namespace App\Jobs;

use App\Mail\SendEmailContact;
use App\Mail\SendEmailOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
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
        $email = new SendEmailOrder($this->order, 'admin', $user_admin['id']);
        Mail::to($user_admin['email'])->send($email);

        //For partner
        $user_partner = get_user_data($this->order['owner']);
        $email = new SendEmailOrder($this->order, 'partner', $this->order['owner']);
        Mail::to($user_partner['email'])->send($email);

        //For customer
        $email = new SendEmailOrder($this->order, 'customer', $this->order['buyer']);
        Mail::to($this->order['email'])->send($email);
    }
}
