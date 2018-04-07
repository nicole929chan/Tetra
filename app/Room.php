<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public function project()
    {
    	return $this->belongsTo(Project::class);
    }

    public function selections()
    {
    	return $this->hasMany(Selection::class);
    }
}
