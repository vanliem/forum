<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Thread;

class ReadThreadsTest extends TestCase
{

    protected $thread;

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

    /** @test*/
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        //$threadNotInChannel = create('App\Channel');

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title);
    }

    /** @test*/
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signedIn(create('App\User', ['name' => 'example_name']));

        $threadByExample = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByExample = create('App\Thread');

        $this->get('/threads/?by=' . auth()->user()->name)
            ->assertSee($threadByExample->title)
            ->assertDontSee($threadNotByExample->title);
    }
}
