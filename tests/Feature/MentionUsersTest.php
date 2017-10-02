<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $userA = create('App\User', ['name' => 'userA']);

        $this->signedIn($userA);

        $userB = create('App\User', ['name' => 'userB']);

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => '@userA look at this'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $userA->notifications);
    }
}
