<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
   protected $table = 'gmz_meta';

   protected $fillable = [
      'id',
      'post_id',
      'post_type',
      'meta_key',
      'meta_value'
   ];
}
