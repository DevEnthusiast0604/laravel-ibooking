<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use TorMorten\Eventy\Facades\Eventy;

class Hotel extends Model
{
    use SoftDeletes;

    protected $table = 'gmz_hotel';

    public function __construct(){
        parent::__construct();
        $this->setFillable();
    }

    public function setFillable()
    {
        $fields = Eventy::filter('gmz_hotel_fillable', [
            'post_title', 'post_slug', 'post_content', 'post_description', 'location_lat', 'location_lng', 'location_address', 'location_zoom', 'thumbnail_id', 'gallery', 'base_price', 'extra_services', 'hotel_star', 'hotel_logo', 'video', 'policy', 'checkin_time', 'checkout_time', 'min_day_booking', 'min_day_stay', 'nearby_common', 'nearby_education', 'nearby_health', 'nearby_top_attractions', 'nearby_restaurants_cafes', 'nearby_natural_beauty', 'nearby_airports', 'faq', 'enable_cancellation', 'cancel_before', 'cancellation_detail', 'property_type', 'hotel_facilities', 'hotel_services', 'rating', 'is_featured', 'author', 'status', 'booking_form', 'external_booking', 'external_link'
        ]);
        $this->fillable = $fields;
    }

    public function TermRelation()
    {
        return $this->hasMany(TermRelation::class,'post_id','id');
    }

    public function updateHotel($post_id, $data)
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
        if(!empty($args['status'])){
            if(is_array($args['status'])) {
                $query->whereIn('status', $args['status']);
            }else{
                $query->where('status', $args['status']);
            }
        }
        if(!empty($args['nearby'])){
            $lat = $args['nearby']['lat'];
            $lng = $args['nearby']['lng'];
            $distance = $args['nearby']['distance'];
            $query->selectRaw("( 6371 * acos( cos( radians({$lat}) ) * cos( radians( location_lat ) ) * cos( radians( location_lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( location_lat ) ) ) ) AS distance");
            $query->orHavingRaw("distance <= " . $distance);
            $query->orderBy('distance', 'ASC');
        }else{
            $query->orderBy($args['orderby'], $args['order']);
        }
        return $query->get();
    }
}
