<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarAvailability extends Model
{
    protected $table = 'gmz_car_availability';

    protected $fillable = ['post_id', 'check_in', 'check_out', 'number', 'price', 'booked', 'status', 'is_base'];
}
