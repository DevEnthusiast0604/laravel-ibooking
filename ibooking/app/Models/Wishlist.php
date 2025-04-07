<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wishlist extends Model
{
    use SoftDeletes;

    protected $table = 'gmz_wishlist';

    protected $fillable = [
        'post_id', 'post_type', 'author'
    ];
}
