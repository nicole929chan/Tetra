<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
	protected $guarded = [];

    public function project()
    {
    	return $this->belongsTo(Project::class);
    }

    public function selections()
    {
    	return $this->hasMany(Selection::class);
    }

    public function versions()
    {
    	return $this->hasMany(Version::class);
    }

    public function selection()
    {
        return Selection::find($this->selection);
    }
}
