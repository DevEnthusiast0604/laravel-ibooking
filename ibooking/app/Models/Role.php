<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	public function users(){
		return $this->belongsToMany(User::class);
	}

	public function RoleUser(){
		return $this->hasMany(RoleUser::class,'role_id','id');
	}
}
