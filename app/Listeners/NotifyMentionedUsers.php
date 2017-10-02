<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
{
    public function handle(ThreadReceivedNewReply $event)
    {
        collect($event->reply->mentionedUsers())
        ->map(function ($name) {
            return User::whereName($name)->first();
        })
        ->filter()
        ->each(function ($user) use ($event) {
            $user->notify(new YouWereMentioned($event->reply));
        });
    }
}
