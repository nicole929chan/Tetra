<?php

namespace App\Http\Controllers;

use App\Mark;
use App\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarksController extends Controller
{
	/**
	 * mark list
	 * @param  Version $version 
	 * @return json           
	 */
    public function index(Version $version)
    {
    	$this->authorize('view', $version->room);

    	$marks = $version->marks()->latest()->get();

    	if (request()->expectsJson()) return $marks;
    }

    /**
     * store a mark
     * @param  Request $request 
     * @param  Version $version 
     * @return json       
     */
    public function store(Request $request, Version $version)
    {
    	$this->authorize('update', $version->room);

    	$request->validate([
    		'body' => 'required',
    		'lat' => 'required',
    		'lng' => 'required',
    	]);

    	$mark = $version->marks()->create([
    		'creator_id' => auth()->id(),
    		'body' => $request->body,
    		'lat' => $request->lat,
    		'lng' => $request->lng
    	]);

    	if ($file = $request->file('file_path')) {
    		$project = $version->room->project;

    		$file_name = today()->timestamp . '_' . $file->getClientOriginalName();
    		$mark->file_path = $file->storeAs("files/{$project->id}", $file_name, 'public');
    		$mark->save();
    	}

    	if (request()->expectsJson()) return ['message' => 'Mark Created!', 'mark' => $mark];
    }

    /**
     * update a mark
     * @param  Request $request 
     * @param  Mark    $mark    
     * @return json           
     */
    public function update(Request $request, Mark $mark)
    {
    	$this->authorize('update', $mark);

    	$request->validate(['body' => 'required']);

    	$mark->body = $request->body;
    	$mark->save();

    	if (request()->expectsJson()) return ['message' => 'Mark Updated!', 'mark' => $mark];
    }

    /**
     * destroy a mark
     * @param  Mark   $mark 
     * @return json       
     */
    public function destroy(Mark $mark)
    {
    	$this->authorize('delete', $mark);

    	Storage::disk('public')->delete($mark->file_path);

    	$mark->delete();

    	if (request()->expectsJson()) return ['message' => 'Mark Deleted!'];
    }
}