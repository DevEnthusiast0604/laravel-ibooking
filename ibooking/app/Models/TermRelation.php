<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TermRelation extends Model
{
    use SoftDeletes;

    protected $table = 'gmz_term_relation';

	protected $fillable = [
		'term_id', 'post_id', 'post_type'
	];

    public function Post(){
        return $this->belongsTo(Post::class);
    }

    public function Car(){
        return $this->belongsTo(Car::class);
    }

    public function Term(){
        return $this->belongsTo(Term::class);
    }
}
