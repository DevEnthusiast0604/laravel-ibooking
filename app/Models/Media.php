<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'gmz_media';

    protected $fillable = [
        'media_title', 'media_name', 'media_url',
        'media_path', 'media_description', 'media_size',
        'media_type', 'author'
    ];
}
