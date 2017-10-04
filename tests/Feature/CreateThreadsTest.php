<?php

namespace Tests\Feature;

use App\Activity;
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
        $this->signedIn();

        $thread = make('App\Thread');
        
        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
     * @test
     */
    public function guest_may_not_create_threads()
    {
        $thread = make('App\Thread');
        $this->withExceptionHandling()
            ->post('/threads', $thread->toArray())
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /** @test */

    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');

    }

    /** @test */

    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');

    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signedIn();
        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signedIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, Activity::count());
    }


    /** @test */
    public function unauthorized_user_may_not_delete_threads()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->delete($thread->path())
            ->assertRedirect('login');

        $this->signedIn();
        $this->delete($thread->path())->assertStatus(403);
    }
}

