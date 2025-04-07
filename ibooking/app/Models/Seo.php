<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seo extends Model
{
    use SoftDeletes;

    protected $table = 'gmz_seo';

	protected $fillable = [
		'post_id', 'post_type', 'seo_title', 'meta_description', 'seo_image_facebook', 'seo_title_facebook', 'meta_description_facebook', 'seo_image_twitter', 'seo_title_twitter', 'meta_description_twitter'
	];
}
