<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
	protected $table = 'role_user';

	protected $fillable = [
		'role_id', 'user_id'
	];

	public function User(){
		return $this->belongsTo(User::class);
	}

	public function Role(){
		return $this->belongsTo(Role::class);
	}
}
