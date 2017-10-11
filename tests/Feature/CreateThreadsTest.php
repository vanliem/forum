<?php

namespace Tests\Feature;

use App\Thread;
use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function a_user_can_create_new_forum_threads()
    {
        $this->signedIn();

        $thread = make('App\Thread');
        
        $response = $this->post(route('threads.store'), $thread->toArray());

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
            ->post(route('threads.store'), $thread->toArray())
            ->assertStatus(302)
            ->assertRedirect(route('login'));
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

        return $this->post(route('threads.store'), $thread->toArray());
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

    /** @test */
    public function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory('App\User')->states('unconfirmed')
            ->create();


        $this->signedIn($user);

        $thread = make('App\Thread');

        $this->post(route('threads.store'), $thread->toArray())
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash', 'You are not authorized');
    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->signedIn();

        $thread = create('App\Thread', ['title' => 'test unique']);

        $this->assertEquals($thread->fresh()->slug, 'test-unique');

        $response = $this->postJson(route('threads.store'), $thread->toArray())->json();

        $this->assertEquals('test-unique-' . md5($response['id']), $response['slug']);
    }

    /** @test */
    public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signedIn();

        $thread = create('App\Thread', ['title' => 'Some title 24']);

        $response = $this->postJson(route('threads.store'), $thread->toArray())->json();

        $this->assertEquals('some-title-24-' . md5($response['id']), $response['slug']);
    }
}

