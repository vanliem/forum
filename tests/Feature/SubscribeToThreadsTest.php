<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signedIn();

        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscriptions')->json();

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some reply here'
        ]);

    }
}
