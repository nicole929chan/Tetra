<?php

namespace App\Http\Controllers;

use App\Room;
use App\Version;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    public function show(Room $room, Version $version = null)
    {
    	if (is_null($version)) {
    		$version = Version::find($room->version);
    	}

    	return view('rooms.show', compact('room', 'version'));
    }
}
