<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThreadReceivedNewReply
{
    use Dispatchable, SerializesModels;

    public $reply;

    public function __construct($reply)
    {
        $this->reply = $reply;
    }
}
