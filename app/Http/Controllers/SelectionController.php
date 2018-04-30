<?php

namespace App\Http\Controllers;

use App\Room;
use App\Selection;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class SelectionController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * view selection
	 * @return \Illuminate\Http\Response
	 */
    public function index(Room $room)
    {
    	try {
    		$this->authorize('view', $room);
    	} catch (AuthorizationException $e) {
    		return redirect(route('projects.index'));
    	}

    	$selections = $room->selections;

			if($room->selection > 0){
				return redirect('/rooms/'.$room->id);
			} else {
				return view('selection', compact('selections'))->with('room', $room);
			}

    }

    /**
     * display the selection that user submitted
     * @param  Room   $room 
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        $this->authorize('view', $room);

        $project = $room->project;
        $selection = $room->selection();

        if (request()->expectsJson()) 
            return ['project' => $project, 'room' => $room, 'selection' => $selection];

        return view('selections.show', compact('project', 'room', 'selection'));
    }

    /**
     * store the selection submitted from user
     * @param  Selection $selection
     * @return \Illuminate\Http\Response
     */
    public function store(Selection $selection)
    {
        $room = $selection->room;

        $this->authorize('update', $room);

        if ($room->selection > 0) return abort(403, 'The room has a selection already, you cannot submit again.');

        $room->update([
            'selection' => $selection->id,
            'status' => '1'
        ]);

        $selection->updated_at = Carbon::now();
        $selection->save();

        if (request()->expectsJson()) return ['message' => 'The selection has been submitted.', 'room' => $room];

        return redirect(route('selection.index', [$selection->room->id]))->with('flash', 'The selection has been submitted.');
    }
}
