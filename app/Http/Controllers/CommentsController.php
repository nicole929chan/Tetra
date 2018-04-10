<?php

namespace App\Http\Controllers;

use App\Version;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
	/**
	 * get the comments under a version
	 * @param  Version $version 
	 * @return json comment records
	 */
    public function index(Version $version)
    {
    	$this->authorize('view', $version->room);

    	$comments = $version->comments()->latest()->get();

    	if (request()->expectsJson()) return $comments;
    }
}
