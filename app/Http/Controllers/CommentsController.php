<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentsController extends Controller
{
	/**
	 * get the comments under a version
	 * @param  Version $version 
	 * @return json array
	 */
    public function index(Version $version)
    {
    	$this->authorize('view', $version->room);

    	$comments = $version->comments()->latest()->get();

    	if (request()->expectsJson()) return $comments;
    }

    /**
     * store a comment under a version
     * @param  Request $request 
     * @param  Version $version 
     * @return json array          
     */
    public function store(Request $request, Version $version)
    {
    	$this->authorize('update', $version->room);

    	$request->validate(['body' => 'required']);

    	$comment = $version->comments()->create([
    		'creator_id' => auth()->id(),
    		'body' => $request->body,
    	]);

    	if ($file = $request->file('file_path')) {
    		$project = $version->room->project;

    		$file_name = today()->timestamp . '_' . $file->getClientOriginalName();
    		$comment->file_path = $file->storeAs("files/{$project->id}", $file_name, 'public');
    		$comment->save();
    	}

    	if (request()->expectsJson()) return ['message' => 'Comment Created!', 'comment' => $comment->load('creator')->load('replies')];
    }

    /**
     * update comment
     * @param  Request $request 
     * @param  Comment $comment 
     * @param  Version $version 
     * @return json array           
     */
    public function update(Request $request, Comment $comment, Version $version)
    {
    	$this->authorize('update', $comment);

    	$request->validate(['body' => 'required']);

    	$comment->body = $request->body;
    	$comment->save();

    	if (request()->expectsJson()) return ['message' => 'Comment Updated!', 'comment' => $comment];
    }

    public function destroy(Comment $comment)
    {
    	$this->authorize('delete', $comment);
    	
    	Storage::disk('public')->delete($comment->file_path);

    	$comment->delete();

    	if (request()->expectsJson()) return ['message' => 'Comment Deleted!'];
    }
}
