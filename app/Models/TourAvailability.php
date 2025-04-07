<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TorMorten\Eventy\Facades\Eventy;

class TourAvailability extends Model
{
    protected $table = 'gmz_tour_availability';
    protected $primaryKey = 'id';

    public function __construct(array $attributes = [])
    {
        $this->setFillable();
        parent::__construct($attributes);
    }

    public function setFillable()
    {
        $this->fillable =  Eventy::filter('gmz_tour_availability_fillable', ['post_id', 'check_in', 'check_out', 'adult_price', 'children_price', 'infant_price', 'group_size', 'booked', 'status', 'is_base']);
    }
}
