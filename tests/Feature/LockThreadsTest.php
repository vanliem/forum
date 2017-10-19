<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
    	$this->signedIn();

        $thread = create('App\Thread', ['locked' => true]);

        $this->post($thread->path() . '/replies', [
        	'user_id' => create('App\User')->id, 
        	'body' => 'Foobar'
        ])->assertStatus(422);
    }

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
        $this->signedIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('lock-threads.store', $thread))
        ->assertStatus(403);

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    public function a_administrators_can_lock_threads()
    {
        $this->signedIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('lock-threads.store', $thread));

        $this->assertTrue(!! $thread->fresh()->locked);
    }

    /** @test */
    public function a_administrators_can_unlock_threads()
    {
        $this->signedIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked' => true]);

        $this->delete(route('lock-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked);
    }
}
