<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use TorMorten\Eventy\Facades\Eventy;

class Room extends Model
{
    use SoftDeletes;

    protected $table = 'gmz_room';

    public function __construct(){
        parent::__construct();
        $this->setFillable();
    }

    public function setFillable()
    {
        $this->fillable = Eventy::filter('gmz_room_fillable', [
            'post_title', 'post_content', 'thumbnail_id', 'gallery', 'base_price', 'number_of_room', 'room_footage', 'number_of_bed', 'number_of_adult', 'number_of_children', 'room_facilities', 'hotel_id', 'author', 'status'
        ]);
    }

    public function TermRelation()
    {
        return $this->hasMany(TermRelation::class,'post_id','id');
    }

    public function updateRoom($post_id, $data)
    {
        return $this->query()->where('id', $post_id)->update($data);
    }

	public function getPost($post_id){
    	return $this->query()->find($post_id);
	}

    public function getPosts($args){
        $limit = $args['posts_per_page'];
        $query = $this->query();
        $query->selectRaw('*');
        if(!empty($limit) && $limit != -1){
            $query->limit($limit);
        }
        if(!empty($args['post_not_in'])){
            $query->whereNotIn('id', $args['post_not_in']);
        }

        $query->orderBy($args['orderby'], $args['order']);
        return $query->get();
    }
}
