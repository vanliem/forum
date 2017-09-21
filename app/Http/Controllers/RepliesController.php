<?php

namespace App\Http\Controllers;

use App\Channel;
use Illuminate\Http\Request;
use App\Thread;
use App\Reply;

class RepliesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function store($channelId, Thread $thread)
    {
		$data = request()->validate([
			'body' => 'required',			
		]);
		$data['user_id'] = auth()->id();

		$thread->addReply($data);

		return back()
            ->with('flash', 'Your reply has been left.');
    }

    public function destroy(Reply $reply)
    {
    	$this->authorize('delete', $reply);
    	$reply->delete();

    	return back();
    }
}
