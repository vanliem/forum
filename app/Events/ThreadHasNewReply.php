<?php

namespace App\Events;

use App\Thread;
use App\Reply;
use Illuminate\Queue\SerializesModels;

class ThreadHasNewReply
{
    use SerializesModels;

    /**
     * @var Thread
     */
    public $thread;

    /**
     * @var Reply
     */
    public $reply;

    /**
     * ThreadHasNewReply constructor.
     * @param Thread $thread
     * @param $reply
     */
    public function __construct(Thread $thread, $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }
}
