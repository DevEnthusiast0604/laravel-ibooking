<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAvailability extends Model
{
    protected $table = 'gmz_room_availability';

    protected $fillable = ['post_id', 'hotel_id', 'total_room', 'adult_number', 'child_number', 'check_in', 'check_out', 'number', 'price', 'booked', 'status', 'is_base'];
}
