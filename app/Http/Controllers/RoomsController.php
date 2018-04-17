<?php

namespace App\Http\Controllers;

use App\Room;
use App\Version;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    public function show(Room $room, Version $version = null)
    {
        $this->authorize('view', $room);

    	if (is_null($version)) {
    		$version = Version::find($room->version);
    	}

        $project = $room->project;

    	return view('rooms.show', compact('room', 'version', 'project'));
    }

    public function store(Request $request, Room $room)
    {
        $this->authorize('update', $room);

        $version = Version::find($request->version_id);
    	$version->active = false;
    	$version->save();

    	if (request()->expectsJson()) return ['message' => 'Submitting Success!'];

        return redirect(route('rooms.show', [$room->id, $version->id]));
    }
}
