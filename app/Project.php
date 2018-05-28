<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	public function creator()
	{
		return $this->belongsTo(User::class, 'creator_id');
	}
	
    public function users()
    {
    	return $this->belongsToMany(User::class);
    }
}
