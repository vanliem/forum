<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Inspections\Spam;
use Illuminate\Http\Request;
use App\Thread;
use App\Reply;

class RepliesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')
            ->except('index');
	}

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(1);
    }

    public function store($channelId, Thread $thread)
    {
		$data = $this->validateReply();

        $data['user_id'] = auth()->id();

        $reply = $thread->addReply($data);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

		return back()
            ->with('flash', 'Your reply has been left.');
    }

    public function destroy(Reply $reply)
    {
    	$this->authorize('delete', $reply);
    	$reply->delete();

        if (request()->wantsJson()) {
            return response(['status' => 'Reply deleted.']);
        }

    	return back()->with('flash', 'Your reply has been deleted.');
    }

    public function update(Reply $reply)
    {
    	$this->authorize('update', $reply);

    	$data = $this->validateReply();

    	$reply->update($data);
    }

    protected function validateReply()
    {
        $data = request()->validate([
            'body' => 'required',
        ]);

        resolve(Spam::class)->detect($data['body']);

        return $data;
    }
}
