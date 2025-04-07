<?php

namespace App\Plugins\ICal\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Apartment extends Model
{
    protected $table = 'gmz_apartment';

    /**
     * @return Collection
     */
    public function getIcalsData(){
        return $this->whereNotNull('ical')->where('status', 'publish')->get();
    }
}
