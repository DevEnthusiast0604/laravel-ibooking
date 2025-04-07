<?php

namespace App\Plugins\Invoice\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $table = 'gmz_order';

    /**
     * @return Collection
     */
    public function getOrderItem($orderToken){
        return $this->where('order_token', $orderToken)->first();
    }
}
