<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
   protected $table = 'gmz_agent';

   protected $fillable = [
      'id',
      'post_title',
      'post_content',
      'thumbnail_id',
      'gallery',
      'quantity',
      'video',
      'post_type',
      'author',
      'status',
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
         $query->whereNotIn('id', $args['post_not_in']);
      }
      if (!empty($args['nearby'])) {
         $lat = $args['nearby']['lat'];
         $lng = $args['nearby']['lng'];
         $distance = $args['nearby']['distance'];
         $query->selectRaw("( 6371 * acos( cos( radians({$lat}) ) * cos( radians( location_lat ) ) * cos( radians( location_lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( location_lat ) ) ) ) AS distance");
         $query->orHavingRaw("distance <= " . $distance);
         $query->orderBy('distance', 'ASC');
      } else {
         $query->orderBy($args['orderby'], $args['order']);
      }
      return $query->get();
   }

}
