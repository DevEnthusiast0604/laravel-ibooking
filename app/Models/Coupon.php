<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'gmz_coupon';

	protected $fillable = [
		'code', 'description', 'start_date', 'end_date', 'percent', 'author', 'status'
	];
}
