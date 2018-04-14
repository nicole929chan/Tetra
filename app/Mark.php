<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $guarded = [];
    
    protected $with = ['replies'];

    public function version()
    {
    	return $this->belongsTo(Version::class);
    }

    public function creator()
    {
    	return $this->belongsTo(User::class, 'creator_id');
    }

    public function replies()
    {
    	return $this->morphMany(Reply::class, 'repliable');
    }
}
