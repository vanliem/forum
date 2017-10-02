<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Forms\CreatePostForm;
use App\Inspections\Spam;
use App\Rules\SpamFree;
use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use Illuminate\Support\Facades\Gate;

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

    public function store($channelId, Thread $thread, CreatePostForm $form)
    {
        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])
        ->load('owner');
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

        try {
            $data = request()->validate([
                'body' => [
                    'required',
                    new SpamFree
                ],
            ]);

            $reply->update($data);

        } catch(\Exception $e) {
            return response('Your reply is spam', 422);
        }
    }
}
