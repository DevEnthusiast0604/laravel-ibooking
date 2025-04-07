<?php

namespace App\Plugins\ICal\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoomAvailability extends Model
{
    protected $table = 'gmz_room_availability';

    protected $fillable = ['post_id', 'hotel_id', 'total_room', 'adult_number', 'child_number', 'check_in', 'check_out', 'number', 'price', 'booked', 'status', 'is_base'];

    public function getNullRoomItem($roomID){
        $data = $this->where('post_id', $roomID)
            ->whereRaw('check_in IS NULL')
            ->whereRaw('check_out IS NULL')
            ->first();
        return $data;
    }

    public function getItem($roomID, $checkIn){
        $data = $this->where('post_id', $roomID)
            ->where('check_in', $checkIn)
            ->first();
        return $data;
    }

    /**
     * @param $id
     * @param $from
     * @param $hotelID
     * @return Collection
     */
    public function getUnavailableData($id, $from, $hotelID)
    {
        $data = $this->where('check_in', '>=', $from)
            ->where('post_id', $id)
            ->where('hotel_id', $hotelID)
            ->whereRaw('(status = "unavailable" OR (IFNULL(number, 0) - IFNULL(booked, 0)) <= 0)')
            ->get();
        return $data;
    }
}
