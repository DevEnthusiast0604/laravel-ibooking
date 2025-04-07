<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'gmz_options';

	protected $fillable = [
		'name', 'value'
	];
}
