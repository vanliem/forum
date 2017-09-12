<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Thread;

class ThreadsTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        $response = $this->get('/threads');

        $response->assertSee($this->thread->title);

    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $response = $this->get($this->thread->path());
        
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_replies_that_are_associated_with_a_read()
    {
        //And that thread includes replies
        $reply = factory('App\Reply')
            ->create(['thread_id' => $this->thread->id]);
        //When we visit a thread page
        $response = $this->get($this->thread->path());
        $response->assertSee($reply->body);
        //Then we should see the replies
    }


}
