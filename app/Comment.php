<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $guarded = [];

    protected $with = ['replies', 'creator:id,name'];

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

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }
}
