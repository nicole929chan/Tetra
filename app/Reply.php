<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	protected $guarded = [];
	
    public function owner()
    {
    	return $this->belongsTo(User::class, 'owner_id');
    }

    public function repliable()
    {
    	return $this->morphTo();
    }
}
