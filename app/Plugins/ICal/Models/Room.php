<?php

namespace App\Plugins\ICal\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    protected $table = 'gmz_room';

    /**
     * @return Collection
     */
    public function getIcalsData(){
        return $this->whereNotNull('ical')->where('status', 'publish')->get();
    }
}
