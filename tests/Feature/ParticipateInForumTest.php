<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
	use RefreshDatabase;

    /**
     * @test
     */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling()
            ->post('threads/channel-slug/1/replies', [])
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        //Give We have an authenticated user
        $this->be($user = factory('App\User')->create());
        
        // Have a existing thread
        $thread = factory('App\Thread')->create();

        //When the user adds a rely to the thread
        $reply = factory('App\Reply')->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        //Then their reply should be visible on the page.
        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signedIn();
        $reply = make('App\Reply', ['body' => '']);

        $thread = create('App\Thread');

        return $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
