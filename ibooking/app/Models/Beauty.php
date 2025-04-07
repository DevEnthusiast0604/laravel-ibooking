<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beauty extends Model
{
    use SoftDeletes;

    protected $table = 'gmz_beauty';

    protected $fillable = [
        'id',
        'post_title',
        'post_slug',
        'post_content',
        'post_description',
        'location_lat',
        'location_lng',
        'location_address',
        'location_zoom',
        'location_state',
        'location_postcode',
        'location_country',
        'location_city',
        'thumbnail_id',
        'gallery',
        'base_price',
        'booking_form',
        'enable_cancellation',
        'cancel_before',
        'cancellation_detail',
        'quantity',
        'rating',
        'is_featured',
        'video',
        'author',
        'status',
        'service',
        'available_space',
        'service_starts',
        'service_ends',
        'service_duration',
        'branch',
        'day_off_week',
        'external_booking', 'external_link'
    ];

    public function TermRelation()
    {
        return $this->hasMany(TermRelation::class, 'post_id', 'id');
    }

    public function updateBeauty($post_id, $data)
    {
        return $this->query()->where('id', $post_id)->update($data);
    }

    public function getPost($post_id)
    {
        return $this->query()->find($post_id);
    }

    public function getPosts($args)
    {
        $limit = $args['posts_per_page'];
        $query = $this->query();
        $query->selectRaw('*');
        if (!empty($limit) && $limit != -1) {
            $query->limit($limit);
        }
        if (!empty($args['post_not_in'])) {
            $query->whereNotIn('gmz_beauty.id', $args['post_not_in']);
        }
        if (!empty($args['status'])) {
            if (is_array($args['status'])) {
                $query->whereIn('status', $args['status']);
            } else {
                $query->where('status', $args['status']);
            }
        }
        if (!empty($args['nearby'])) {
            $lat = $args['nearby']['lat'];
            $lng = $args['nearby']['lng'];
            $distance = $args['nearby']['distance'];
            $query->selectRaw("( 6371 * acos( cos( radians({$lat}) ) * cos( radians( location_lat ) ) * cos( radians( location_lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( location_lat ) ) ) ) AS distance");
            $query->orHavingRaw("distance <= " . $distance);
            $query->orderBy('distance', 'ASC');
        } else {
            $query->orderBy('gmz_beauty.id', $args['order']);
        }
        if (!empty($args['terms_not_in']) || !empty($args['terms'])) {
            $query->join('gmz_term_relation', 'gmz_beauty.id', 'gmz_term_relation.post_id');
            $query->join('gmz_term', 'gmz_term_relation.term_id', 'gmz_term.id');
            $query->where('gmz_term_relation.post_type', GMZ_SERVICE_BEAUTY);
            if (!empty($args['terms'])) {
                $query->whereIn('gmz_term.id', $args['terms']);
            }

            if (!empty($args['terms_not_in'])) {
                $query->whereNotIn('gmz_term.id', $args['terms_not_in']);
            }
        }
        return $query->get();
    }

    public function agent()
    {
        return $this->belongstoMany(Agent::class, 'gmz_agent_relation', 'post_id', 'agent_id');
    }

    public function service()
    {
        return $this->belongsTo(Term::class, 'service', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Term::class, 'branch', 'id');
    }

}
