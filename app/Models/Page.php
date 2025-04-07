<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $table = 'gmz_page';

	protected $fillable = [
		'post_title', 'post_slug', 'post_content', 'thumbnail_id', 'status', 'author'
	];

    public function getPosts($args){
        $limit = $args['posts_per_page'];
        $query = $this->query();
        if(!empty($limit) && $limit != -1){
            $query->limit($limit);
        }
        return $query->orderBy($args['orderby'], $args['order'])->get();
    }

	public function getPost($post_id){
		return $this->query()->find($post_id);
	}
}
