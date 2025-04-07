<?php

namespace App\Plugins\ICal\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TourAvailability extends Model
{
    protected $table = 'gmz_tour_availability';

    protected $fillable = ['post_id', 'check_in', 'check_out', 'adult_price', 'children_price', 'infant_price', 'group_size', 'booked', 'status', 'is_base'];

    public function getItem($postID, $checkIn){
        $data = $this->where('post_id', $postID)
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
    public function getUnavailableData($id, $from)
    {
        $data = $this->where('check_in', '>=', $from)
            ->where('post_id', $id)
            ->whereRaw('(status = "unavailable" OR (IFNULL(group_size, 0) - IFNULL(booked, 0)) <= 0)')
            ->get();
        return $data;
    }
}
