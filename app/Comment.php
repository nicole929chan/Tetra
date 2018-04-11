<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $guarded = [];
	
    public function version()
    {
    	return $this->belongsTo(Version::class);
    }

    public function creator()
    {
    	return $this->belongsTo(User::class, 'creator_id');
    }
}
