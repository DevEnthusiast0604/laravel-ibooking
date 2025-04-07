<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use SoftDeletes;

    protected $table = 'gmz_car';

    protected $fillable = [
        'post_title', 'post_slug', 'post_content', 'post_description', 'location_lat', 'location_lng', 'location_address', 'location_zoom', 'thumbnail_id', 'gallery', 'base_price', 'booking_form', 'enable_cancellation', 'cancel_before', 'cancellation_detail', 'quantity', 'equipments', 'car_type', 'car_feature', 'car_equipment', 'rating', 'is_featured', 'extra_price', 'discount_by_day', 'insurance_plan', 'passenger', 'gear_shift', 'baggage', 'door', 'video', 'author', 'status', 'external_booking', 'external_link'
    ];

    public function TermRelation()
    {
        return $this->hasMany(TermRelation::class,'post_id','id');
    }

    public function updateCar($post_id, $data)
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

    public function test($data){
        $query = $this->query();


        $query->select("*");

        if (!empty($data['lat']) && !empty($data['lng'])) {
            $distance = get_option('car_search_radius', '25');
            $distance = floatval($distance);
            $data['lat'] = esc_sql($data['lat']);
            $data['lng'] = esc_sql($data['lng']);
            $query->selectRaw("( 6371 * acos( cos( radians({$data['lat']}) ) * cos( radians( {$this->table}.location_lat ) ) * cos( radians( {$this->table}.location_lng ) - radians({$data['lng']}) ) + sin( radians({$data['lat']}) ) * sin( radians( {$this->table}.location_lat ) ) ) ) AS distance");
            $query->orHavingRaw("distance <= " . $distance);
            $query->orderByDesc('distance');
        } elseif (!empty($data['address'])) {
            $address = urldecode($data['address']);
            $data['address'] = esc_sql($data['address']);
            $query->whereRaw("{$this->table}.location_address LIKE '%{$address}%'");
            $query->orderBy("{$this->table}.id", 'ASC');
        }

        if (!empty($data['price_range'])) {
            $min_max = convert_price_range($data['price_range']);
            $query->whereRaw("base_price >= {$min_max['min']} AND base_price <= {$min_max['max']}");
        }

        if(!empty($data['checkIn']) && !empty($data['checkOut'])){

            $avai_table = 'gmz_car_availability';

            $check_in = strtotime($data['checkIn']);
            $check_out = strtotime($data['checkOut']);

            //Check with availability
            $unavailable_car = "SELECT post_id
                FROM {$avai_table}
                WHERE
                    (status <> 'available')
                    AND
                    (
                        ({$avai_table}.check_in <= {$check_in} AND {$avai_table}.check_out >= {$check_out})
                        OR
                        ({$avai_table}.check_in >= {$check_in} AND {$avai_table}.check_out <= {$check_out})
                        OR
                        ({$avai_table}.check_in <= {$check_in} AND {$avai_table}.check_out >= {$check_in})
                        OR
                        ({$avai_table}.check_in <= {$check_out} AND {$avai_table}.check_out >= {$check_out})
                    )";

            $query->whereRaw("{$this->table}.id NOT IN ({$unavailable_car})");

            //Check with booking table
            //doing...
        }

        if (!empty($data['car-type'])) {
            $car_type_arr = explode(',', $data['car-type']);
            $sql_car_type = [];
            foreach ($car_type_arr as $k => $v) {
                array_push($sql_car_type, "( FIND_IN_SET({$v}, {$this->table}.car_type) )");
            }
            if (!empty($sql_car_type)) {
                $query->whereRaw("(" . implode(' OR ', $sql_car_type) . ")");
            }
        }

        if (!empty($data['car-feature'])) {
            $car_feature_arr = explode(',', $data['car-feature']);
            $sql_car_feature = [];
            foreach ($car_feature_arr as $k => $v) {
                array_push($sql_car_feature, "( FIND_IN_SET({$v}, car.car_feature) )");
            }
            if (!empty($sql_car_feature)) {
                $query->whereRaw("(" . implode(' OR ', $sql_car_feature) . ")");
            }
        }

        $query->whereRaw("{$this->table}.status = 'publish'");
        return $query->paginate($data['number'], '', '', $data['page']);
    }
}
