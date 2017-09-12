<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        //Give we have a signed in user
        $this->signedIn();

        //When we hit the Endpoint to create a new thread
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

        //Then, When we visit the thread page, We should see the new thread
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
     * @test
     */
    public function guest_may_not_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());
    }
}
