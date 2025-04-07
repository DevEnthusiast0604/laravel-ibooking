<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $table = 'gmz_term';

	protected $fillable = [
		'term_title', 'term_name', 'term_description', 'term_icon', 'term_image', 'term_price', 'taxonomy_id', 'parent', 'term_location', 'author'
	];

    public function TermRalation(){
        return $this->hasMany(TermRelation::class,'term_id','id');
    }

    public function Taxonomy(){
        return $this->belongsTo(Taxonomy::class);
    }

	// One level child
	public function child() {
		return $this->hasMany('App\Models\Term', 'parent');
	}

	// Recursive children
	public function children() {
		return $this->hasMany('App\Models\Term', 'parent')
		            ->with('children');
	}

	// One level parent
	public function parent() {
		return $this->belongsTo('App\Models\Term', 'parent');
	}

	// Recursive parents
	public function parents() {
		return $this->belongsTo('App\Models\Term', 'parent')
		            ->with('parent');
	}
}
