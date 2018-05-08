<?php

namespace App\Http\Controllers;

use App\Version;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
	/**
	 * mark and comment list
	 * @param  Version $version
	 * @return json
	 */
    public function index(Version $version)
    {
    	$this->authorize('view', $version->room);

    	$marks = $version->marks;
    	$comments = $version->comments;

    	// $activities = $marks->concat($comments)->sortByDesc('created_at');
        $activities = $marks->concat($comments);
        $sorted = $activities->sortByDesc('created_at');
        $activities = $sorted->values()->all();

        foreach ($activities as $key => $activity) {
            $activity['uniqueId'] = $key + 1;
        }

    	if (request()->expectsJson()) return $activities;
    }
}
