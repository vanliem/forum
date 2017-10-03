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

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadsTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadsTwoReplies->id], 2);

        $threadsThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadsThreeReplies->id],3);

        $threadNoReply = $this->thread;

        $response = $this->getJson('/threads/?popular=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count'));
    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id] , 3);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertEquals(3, $response['total']);
    }

    /** @test */
    public function a_user_can_filter_threads_by_those_are_unanswered()
    {
        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->getJson('/threads/?unanswered=1')->json();

        $this->assertCount(1, $response['data']);
    }
}
