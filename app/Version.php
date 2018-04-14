<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    public function room()
    {
    	return $this->belongsTo(Room::class);
    }

    public function comments()
    {
    	return $this->hasMany(Comment::class);
    }

    public function marks()
    {
    	return $this->hasMany(Mark::class);
    }
}
