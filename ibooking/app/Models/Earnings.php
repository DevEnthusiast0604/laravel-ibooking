<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Earnings extends Model
{
   protected $table = 'gmz_earnings';

   protected $fillable = [
      'user_id','total','balance','net_earnings'
   ];
}
