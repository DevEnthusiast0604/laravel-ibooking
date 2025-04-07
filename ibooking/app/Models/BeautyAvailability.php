<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeautyAvailability extends Model
{
    protected $table = 'gmz_beauty_availability';

    protected $fillable = ['post_id', 'check_in', 'check_out', 'price', 'status'];
}
