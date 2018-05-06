<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RepliesController extends Controller
{
	public function __construct()
	{
		return $this->middleware('auth');
	}

	/**
	 * store a reply
	 * @param  Request $request 
	 * @param  Comment $comment 
	 * @return json array
	 */
    public function store(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment->version->room);

    	$request->validate(['body' => 'required']);

    	$reply = $comment->replies()->create([
    		'owner_id' => auth()->id(),
    		'body' => $request->body
    	]);

    	$file = $request->file('file_path');

    	if ($file = $request->file('file_path')) {
    		$project = $comment->version->room->project;

    		$file_name = today()->timestamp . '_' . $file->getClientOriginalName();
    		$reply->file_path = $file->storeAs("files/{$project->id}", $file_name, 'public');
    		$reply->save();
    	}

    	if (request()->expectsJson()) return ['message' => 'Reply Created!', 'reply' => $reply->load('owner')];
    }

    /**
     * update a reply
     * @param  Request $request
     * @param  Reply   $reply   
     * @return json array
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $request->validate(['body' => 'required']);

        $reply->body = $request->body;

        $reply->save();

        if (request()->expectsJson()) return ['message' => 'Reply Updated!', 'reply' => $reply];
    }

    /**
     * destroy a reply
     * @param  Reply  $reply 
     * @return json array
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);

        Storage::disk('public')->delete($reply->file_path);

        $reply->delete();

        if (request()->expectsJson()) return ['message' => 'Reply Deleted!'];
    }
}
